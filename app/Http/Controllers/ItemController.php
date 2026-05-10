<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Producto;
use App\Models\Insumo;
use App\Models\CategoriaProducto;
use App\Models\CategoriaInsumo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ItemController extends Controller
{
    /**
     * Display a unified listing of all items (productos + insumos).
     */
    public function index(Request $request)
    {
        $filtro = $request->get('filtro', 'todos');
        $buscar = $request->get('buscar', '');
        $categoria = $request->get('categoria', '');
        
        // Query base con relaciones
        $items = Item::with(['producto.categoria', 'insumo.categoria'])
            ->when($buscar, function($query) use ($buscar) {
                return $query->where('nombre', 'like', "%{$buscar}%");
            })
            ->when($filtro === 'productos', function($query) {
                return $query->where('tipo_item', 'producto');
            })
            ->when($filtro === 'insumos', function($query) {
                return $query->where('tipo_item', 'insumo');
            })
            ->when($categoria && $filtro === 'productos', function($query) use ($categoria) {
                return $query->whereHas('producto', function($q) use ($categoria) {
                    $q->where('id_cat_producto', $categoria);
                });
            })
            ->when($categoria && $filtro === 'insumos', function($query) use ($categoria) {
                return $query->whereHas('insumo', function($q) use ($categoria) {
                    $q->where('id_cat_insumo', $categoria);
                });
            })
            ->orderBy('nombre', 'asc')
            ->paginate(15)
            ->appends($request->all());
        
        // Obtener categorías para los filtros
        $categoriasProductos = CategoriaProducto::orderBy('nombre')->get();
        $categoriasInsumos = CategoriaInsumo::orderBy('nombre')->get();
        
        // Contadores
        $totalItems = Item::count();
        $totalProductos = Item::where('tipo_item', 'producto')->count();
        $totalInsumos = Item::where('tipo_item', 'insumo')->count();
        
        return view('item.index', compact(
            'items', 
            'filtro', 
            'buscar', 
            'categoria',
            'categoriasProductos',
            'categoriasInsumos',
            'totalItems',
            'totalProductos',
            'totalInsumos'
        ));
    }

    /**
     * Show the form for creating a new item.
     */
    public function create()
    {
        $categoriasProductos = CategoriaProducto::orderBy('nombre')->get();
        $categoriasInsumos = CategoriaInsumo::orderBy('nombre')->get();
        
        return view('item.create', compact('categoriasProductos', 'categoriasInsumos'));
    }

    /**
     * Store a newly created item (producto o insumo).
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_item' => 'required|in:producto,insumo',
            'nombre' => 'required|string|max:255',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad,docena,paquete,bandeja',
            // Campos para producto
            'id_cat_producto' => 'required_if:tipo_item,producto|exists:categoria_producto,id_cat_producto',
            'precio' => 'required_if:tipo_item,producto|nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
            // Campos para insumo
            'id_cat_insumo' => 'required_if:tipo_item,insumo|exists:categoria_insumo,id_cat_insumo',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // 1. Crear el Item base
            $item = Item::create([
                'nombre' => $validated['nombre'],
                'tipo_item' => $validated['tipo_item'],
                'unidad_medida' => $validated['unidad_medida'],
            ]);

            // 2. Crear Producto o Insumo según tipo
            if ($validated['tipo_item'] === 'producto') {
                $productoData = [
                    'id_item' => $item->id_item,
                    'id_cat_producto' => $validated['id_cat_producto'],
                    'precio' => $validated['precio'],
                ];

                if ($request->hasFile('imagen')) {
                    // Guardar en storage/app/public/productos/
                    $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
                } elseif ($request->filled('imagen_url')) {
                    // Guardar URL externa tal cual
                    $productoData['imagen'] = $request->imagen_url;
                }

                Producto::create($productoData);
            } else {
                Insumo::create([
                    'id_item' => $item->id_item,
                    'id_cat_insumo' => $validated['id_cat_insumo'],
                    'precio_compra' => $validated['precio_compra'] ?? null,
                ]);
            }

            DB::commit();
            return redirect()->route('items.index')
                ->with('success', ucfirst($validated['tipo_item']) . ' creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified item.
     */
    public function show(Item $item)
    {
        $item->load(['producto.categoria', 'insumo.categoria', 'almacenItems.almacen']);
        return view('item.show', compact('item'));
    }

    /**
     * Show the form for editing the specified item.
     */
    public function edit(Item $item)
    {
        $item->load(['producto', 'insumo']);
        $categoriasProductos = CategoriaProducto::orderBy('nombre')->get();
        $categoriasInsumos = CategoriaInsumo::orderBy('nombre')->get();
        
        return view('item.edit', compact('item', 'categoriasProductos', 'categoriasInsumos'));
    }

    /**
     * Update the specified item.
     */
    public function update(Request $request, Item $item)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'unidad_medida' => 'required|string|in:kg,g,lb,oz,L,mL,unidad,docena,paquete,bandeja',
            // Campos para producto
            'id_cat_producto' => 'required_if:tipo_item,producto|exists:categoria_producto,id_cat_producto',
            'precio' => 'required_if:tipo_item,producto|nullable|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
            // Campos para insumo
            'id_cat_insumo' => 'required_if:tipo_item,insumo|exists:categoria_insumo,id_cat_insumo',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Actualizar Item base
            $item->update([
                'nombre' => $validated['nombre'],
                'unidad_medida' => $validated['unidad_medida'],
            ]);

            // Actualizar Producto o Insumo
            if ($item->tipo_item === 'producto' && $item->producto) {
                $productoData = [
                    'id_cat_producto' => $validated['id_cat_producto'] ?? $item->producto->id_cat_producto,
                    'precio' => $validated['precio'] ?? $item->producto->precio,
                ];

                if ($request->hasFile('imagen')) {
                    $productoData['imagen'] = $request->file('imagen')->store('productos', 'public');
                } elseif ($request->filled('imagen_url')) {
                    $productoData['imagen'] = $validated['imagen_url'];
                }

                $item->producto->update($productoData);
            } elseif ($item->tipo_item === 'insumo' && $item->insumo) {
                $item->insumo->update([
                    'id_cat_insumo' => $validated['id_cat_insumo'] ?? $item->insumo->id_cat_insumo,
                    'precio_compra' => $validated['precio_compra'] ?? $item->insumo->precio_compra,
                ]);
            }

            DB::commit();
            return redirect()->route('items.index')
                ->with('success', 'Item actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified item and its relations.
     */
    public function destroy(Item $item)
    {
        DB::beginTransaction();
        try {
            // Eliminar relaciones en almacen_items
            \App\Models\AlmacenItem::where('id_item', $item->id_item)->delete();
            
            // Eliminar producto o insumo
            if ($item->producto) {
                $item->producto->delete();
            }
            if ($item->insumo) {
                // Verificar que no tenga recetas o compras
                if ($item->insumo->detallesReceta()->count() > 0 || $item->insumo->detallesCompra()->count() > 0) {
                    DB::rollBack();
                    return redirect()->route('items.index')
                        ->with('error', 'No se puede eliminar: tiene recetas o compras asociadas');
                }
                $item->insumo->delete();
            }
            
            // Eliminar item base
            $item->delete();

            DB::commit();
            return redirect()->route('items.index')
                ->with('success', 'Item eliminado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('items.index')
                ->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
}