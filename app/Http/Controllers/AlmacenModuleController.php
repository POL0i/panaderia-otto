<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use App\Models\AlmacenItem;
use App\Models\CategoriaInsumo;
use App\Models\CategoriaProducto;
use App\Models\Insumo;
use App\Models\Item;
use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AlmacenModuleController extends Controller
{
    /**
     * Panel principal del módulo de almacén
     */
    public function index()
    {
        $totalAlmacenes = Almacen::count();
        $totalProductos = Producto::count();
        $totalInsumos = Insumo::count();
        $totalItems = Item::count();
        
        $almacenes = Almacen::withCount('items')  // ← Asegúrate que 'items' sea el nombre de la relación
        ->orderBy('nombre')
        ->get();
        $categoriasInsumo = CategoriaInsumo::orderBy('nombre')->get();
        $categoriasProducto = CategoriaProducto::orderBy('nombre')->get();
        $items = Item::with(['producto', 'insumo'])->orderBy('id_item')->get();
        
        // Últimos almacenes con sus items
        $ultimosAlmacenes = Almacen::withCount('items')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('modulo-almacen.index', compact(
            'totalAlmacenes',
            'totalProductos',
            'totalInsumos',
            'totalItems',
            'almacenes',
            'categoriasInsumo',
            'categoriasProducto',
            'items',
            'ultimosAlmacenes'
        ));
    }

    // ============================================
    // ALMACENES
    // ============================================
    public function storeAlmacen(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:almacenes,nombre',
            'ubicacion' => 'nullable|string|max:255',
            'capacidad' => 'nullable|numeric|min:0',
        ]);

        $almacen = Almacen::create($validated);

        return response()->json([
            'success' => true,
            'almacen' => $almacen,
            'message' => 'Almacén creado exitosamente'
        ]);
    }

    // ============================================
    // CATEGORÍAS DE INSUMO
    // ============================================
    public function storeCategoriaInsumo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria_insumo,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = CategoriaInsumo::create($validated);

        return response()->json([
            'success' => true,
            'categoria' => $categoria,
            'message' => 'Categoría de insumo creada'
        ]);
    }

    // ============================================
    // INSUMOS (crea Item automáticamente)
    // ============================================
    public function storeInsumo(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::create([
                'tipo_item' => 'insumo',
                'unidad_medida' => $validated['unidad_medida'],
            ]);

            $insumo = Insumo::create([
                'id_item' => $item->id_item,
                'id_cat_insumo' => $validated['id_cat_insumo'],
                'nombre' => $validated['nombre'],
                'precio_compra' => $validated['precio_compra'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'insumo' => $insumo->load('categoria', 'item'),
                'message' => 'Insumo creado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // CATEGORÍAS DE PRODUCTO
    // ============================================
    public function storeCategoriaProducto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria_producto,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = CategoriaProducto::create($validated);

        return response()->json([
            'success' => true,
            'categoria' => $categoria,
            'message' => 'Categoría de producto creada'
        ]);
    }

    // ============================================
    // PRODUCTOS (crea Item automáticamente)
    // ============================================
    public function storeProducto(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_producto' => 'required|exists:categoria_producto,id_cat_producto',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad',
            'precio' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $item = Item::create([
                'tipo_item' => 'producto',
                'unidad_medida' => $validated['unidad_medida'],
            ]);

            $producto = Producto::create([
                'id_item' => $item->id_item,
                'id_cat_producto' => $validated['id_cat_producto'],
                'nombre' => $validated['nombre'],
                'precio' => $validated['precio'] ?? null,
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'producto' => $producto->load('categoria', 'item'),
                'message' => 'Producto creado exitosamente'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // ============================================
    // STOCK (AlmacenItem)
    // ============================================
    public function storeStock(Request $request)
    {
        $validated = $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
            'stock' => 'required|numeric|min:0',
        ]);

        // Verificar si ya existe el registro
        $almacenItem = AlmacenItem::where([
            'id_almacen' => $validated['id_almacen'],
            'id_item' => $validated['id_item']
        ])->first();

        if ($almacenItem) {
            // Actualizar stock (sumar o reemplazar según prefieras; aquí reemplazamos)
            $almacenItem->update(['stock' => $validated['stock']]);
            $message = 'Stock actualizado correctamente';
        } else {
            $almacenItem = AlmacenItem::create($validated);
            $message = 'Stock agregado correctamente';
        }

        return response()->json([
            'success' => true,
            'almacen_item' => $almacenItem->load('almacen', 'item'),
            'message' => $message
        ]);
    }

    /**
     * Obtener items de un almacén específico (para mostrar en tabla)
     */
    public function getItemsAlmacen($id)
    {
        $almacen = Almacen::with(['items.item.producto', 'items.item.insumo'])->findOrFail($id);
        
        $items = $almacen->items->map(function($almacenItem) {
            $item = $almacenItem->item;
            $nombre = $item->producto ? $item->producto->nombre : ($item->insumo ? $item->insumo->nombre : 'Item #' . $item->id_item);
            $tipo = $item->tipo_item;
            return [
                'id_item' => $item->id_item,
                'nombre' => $nombre,
                'tipo' => $tipo,
                'stock' => $almacenItem->stock,
                'unidad' => $item->unidad_medida
            ];
        });

        return response()->json([
            'success' => true,
            'almacen' => $almacen->nombre,
            'items' => $items
        ]);
    }
}