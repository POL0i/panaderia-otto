<?php

namespace App\Http\Controllers;

use App\Models\Receta;
use App\Models\Insumo;
use Illuminate\Http\Request;

class RecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recetas = Receta::with('detalles')
            ->orderBy('nombre')
            ->paginate(15);

        return view('produccion.recetas.index', compact('recetas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('produccion.recetas.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:recetas,nombre',
            'descripcion' => 'nullable|string',
            'cantidad_requerida' => 'required|numeric|min:0.01',
        ]);

        Receta::create($validated);

        return redirect()->route('recetas.index')
            ->with('success', 'Receta creada correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Receta $receta)
    {
        $receta->load('detalles.insumo');
        return view('produccion.recetas.show', compact('receta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Receta $receta)
    {
        return view('produccion.recetas.edit', compact('receta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Receta $receta)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:recetas,nombre,' . $receta->id_receta . ',id_receta',
            'descripcion' => 'nullable|string',
            'cantidad_requerida' => 'required|numeric|min:0.01',
        ]);

        $receta->update($validated);

        return redirect()->route('recetas.show', $receta)
            ->with('success', 'Receta actualizada correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Receta $receta)
    {
        // Verificar si hay producciones asociadas
        if ($receta->producciones()->count() > 0) {
            return back()->with('error', 'No se puede eliminar una receta que tiene producciones asociadas');
        }

        $receta->detalles()->delete();
        $receta->delete();

        return redirect()->route('recetas.index')
            ->with('success', 'Receta eliminada correctamente');
    }
}
