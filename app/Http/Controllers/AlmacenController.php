<?php

namespace App\Http\Controllers;

use App\Models\Almacen;
use Illuminate\Http\Request;
use App\Models\CategoriaInsumo;
use App\Models\CategoriaProducto;

class AlmacenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $almacenes = Almacen::with('items')
            ->orderBy('nombre')
            ->paginate(15);
        
        $categoriasInsumo = CategoriaInsumo::all(); // o el modelo que uses
        $categoriasProducto = CategoriaProducto::all(); // o el modelo que uses
        
        return view('modulo-almacen.index', compact(
            'totalAlmacenes', 'totalProductos', 'totalInsumos', 'totalItems',
            'almacenes', 'items', 'categoriasInsumo', 'categoriasProducto'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('almacen.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'capacidad' => 'required|numeric|min:1',
        ]);

        Almacen::create($validated);

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Almacen $almacen)
    {
        $almacen->load(['items.item']);
        
        return view('almacen.show', compact('almacen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Almacen $almacen)
    {
        return view('almacen.edit', compact('almacen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Almacen $almacen)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
            'ubicacion' => 'required|string|max:255',
            'capacidad' => 'required|numeric|min:1',
        ]);

        $almacen->update($validated);

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Almacen $almacen)
    {
        $almacen->delete();

        return redirect()->route('almacenes.index')
            ->with('success', 'Almacén eliminado exitosamente');
    }
}
