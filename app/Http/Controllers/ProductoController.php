<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Item;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index()
    {
        $productos = Producto::with(['item', 'categoria'])
            ->join('items', 'productos.id_item', '=', 'items.id_item')
            ->orderBy('items.nombre', 'asc')
            ->select('productos.*')
            ->paginate(15);

        $categorias = CategoriaProducto::with('productos')
            ->orderBy('nombre')
            ->paginate(10);

        return view('producto.index', compact('productos', 'categorias'));
    }

    public function create()
    {
        $categorias = CategoriaProducto::all();
        return view('producto.create', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'id_cat_producto' => 'required|exists:categoria_producto,id_cat_producto',
            'precio' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string|in:unidad,docena,paquete,bandeja,kg,g,L',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
        ]);

        // 1. Crear el Item padre
        $item = Item::create([
            'nombre' => $request->nombre,
            'tipo_item' => 'producto',
            'unidad_medida' => $request->unidad_medida,
        ]);

        // 2. Preparar datos del producto
        $productoData = [
            'id_item' => $item->id_item,
            'id_cat_producto' => $request->id_cat_producto,
            'precio' => $request->precio,
        ];

        // 3. Manejar imagen
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $productoData['imagen'] = $path;
        } elseif ($request->filled('imagen_url')) {
            $productoData['imagen'] = $request->imagen_url;
        }

        // 4. Crear el Producto
        Producto::create($productoData);

        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    public function show(Producto $producto)
    {
        $producto->load(['item', 'categoria', 'detallesVenta']);
        return view('producto.show', compact('producto'));
    }

   public function edit(Producto $producto)
{
    $categorias = CategoriaProducto::all();
    return view('producto.edit', compact('producto', 'categorias'));
}

    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'id_cat_producto' => 'required|exists:categoria_producto,id_cat_producto',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'unidad_medida' => 'required|string|in:unidad,docena,paquete,bandeja,kg,g,L',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
        ]);

        // Actualizar el Item relacionado
        $producto->item->update([
            'nombre' => $request->nombre,
            'unidad_medida' => $request->unidad_medida,
        ]);

        // Preparar datos del producto
        $productoData = [
            'id_cat_producto' => $request->id_cat_producto,
            'precio' => $request->precio,
        ];

        // Manejar imagen
        if ($request->hasFile('imagen')) {
            $path = $request->file('imagen')->store('productos', 'public');
            $productoData['imagen'] = $path;
        } elseif ($request->filled('imagen_url')) {
            $productoData['imagen'] = $request->imagen_url;
        }

        // Actualizar el Producto
        $producto->update($productoData);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    public function destroy(Producto $producto)
    {
        $item = $producto->item;
        \App\Models\AlmacenItem::where('id_item', $item->id_item)->delete();
        $producto->delete();
        $item->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    // ========== MÉTODOS PARA CATEGORÍAS ==========
    public function storeCategoria(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categoria_producto,nombre',
            'descripcion' => 'nullable|string|max:500',
        ]);

        CategoriaProducto::create($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Categoría creada exitosamente']);
        }

        return redirect()->route('productos.index')
            ->with('success', 'Categoría creada exitosamente');
    }

    public function updateCategoria(Request $request, $id)
    {
        $categoria = CategoriaProducto::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:255|unique:categoria_producto,nombre,' . $id . ',id_cat_producto',
            'descripcion' => 'nullable|string|max:500',
        ]);

        $categoria->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Categoría actualizada exitosamente');
    }

    public function destroyCategoria($id)
    {
        $categoria = CategoriaProducto::findOrFail($id);

        if ($categoria->productos()->count() > 0) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
        }

        $categoria->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }

    public function editCategoria($id)
    {
        $categoria = CategoriaProducto::findOrFail($id);
        return response()->json($categoria);
    }
}
