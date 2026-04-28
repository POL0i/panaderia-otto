<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Item;
use App\Models\CategoriaProducto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $productos = Producto::with(['item', 'categoria'])
            ->orderBy('nombre')
            ->paginate(15);
        
        $categorias = CategoriaProducto::with('productos')
            ->orderBy('nombre')
            ->paginate(10);
        
        return view('producto.index', compact('productos', 'categorias'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $items = Item::where('tipo_item', 'producto')->get();
        $categorias = CategoriaProducto::all();
        
        return view('producto.create', compact('items', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:25',
            'precio' => 'required|numeric|min:0',
            'id_item' => 'required|exists:items,id_item',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
        ]);
        
        $data = [
            'nombre' => $validated['nombre'],
            'precio' => $validated['precio'],
            'id_item' => $validated['id_item'],
        ];
        
        // Priorizar archivo subido sobre URL
        if ($request->hasFile('imagen')) {
            $data['imagen'] = ImageHelper::upload($request->file('imagen'), 'products');
        } elseif ($request->filled('imagen_url')) {
            $data['imagen'] = $request->imagen_url;
        }
        
        $producto = Producto::create($data);
        
        return redirect()->route('productos.index')
            ->with('success', 'Producto creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        $producto->load(['item', 'categoria', 'detallesVenta']);
        
        return view('producto.show', compact('producto'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto)
    {
        $items = Item::where('tipo_item', 'producto')->get();
        $categorias = CategoriaProducto::all();
        
        return view('producto.edit', compact('producto', 'items', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
        $validated = $request->validate([
            'id_cat_producto' => 'required|exists:categoria_producto,id_cat_producto',
            'nombre' => 'required|string|max:255',
            'precio' => 'required|numeric|min:0',
            'imagen' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'imagen_url' => 'nullable|url',
        ]);

        $producto->update($validated);

        return redirect()->route('productos.index')
            ->with('success', 'Producto actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Producto eliminado exitosamente');
    }

    // ========== MÉTODOS PARA CATEGORÍAS DE PRODUCTOS ==========

    /**
     * Store a newly created category in storage.
     */
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

    /**
     * Update the specified category in storage.
     */
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

    /**
     * Remove the specified category from storage.
     */
    public function destroyCategoria($id)
    {
        $categoria = CategoriaProducto::findOrFail($id);
        
        // Verificar si tiene productos asociados
        if ($categoria->productos()->count() > 0) {
            return redirect()->route('productos.index')
                ->with('error', 'No se puede eliminar la categoría porque tiene productos asociados');
        }
        
        $categoria->delete();

        return redirect()->route('productos.index')
            ->with('success', 'Categoría eliminada exitosamente');
    }

    /**
     * Show form for editing category.
     */
    public function editCategoria($id)
    {
        $categoria = CategoriaProducto::findOrFail($id);
        return response()->json($categoria);
    }
}