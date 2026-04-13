<?php

namespace App\Http\Controllers;

use App\Models\DetalleReceta;
use App\Models\Receta;
use App\Models\Insumo;
use Illuminate\Http\Request;

class DetalleRecetaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detalles = DetalleReceta::with('receta', 'insumo')
            ->orderBy('id_receta')
            ->paginate(15);

        return view('produccion.detalles-receta.index', compact('detalles'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $recetas = Receta::all();
        $insumos = Insumo::all();

        return view('produccion.detalles-receta.create', compact('recetas', 'insumos'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_receta' => 'required|exists:recetas,id_receta',
            'id_insumo' => 'required|exists:insumos,id_insumo',
            'cantidad_requerida' => 'required|numeric|min:0.01',
        ]);

        // Verificar que no exista el mismo detalle
        $existe = DetalleReceta::where('id_receta', $validated['id_receta'])
            ->where('id_insumo', $validated['id_insumo'])
            ->exists();

        if ($existe) {
            return back()->with('error', 'Este insumo ya está asociado a esta receta');
        }

        DetalleReceta::create($validated);

        return redirect()->route('detalles-receta.index')
            ->with('success', 'Detalle de receta creado correctamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(DetalleReceta $detalleReceta)
    {
        $detalleReceta->load('receta', 'insumo');
        return view('produccion.detalles-receta.show', compact('detalleReceta'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(DetalleReceta $detalleReceta)
    {
        $recetas = Receta::all();
        $insumos = Insumo::all();

        return view('produccion.detalles-receta.edit', compact('detalleReceta', 'recetas', 'insumos'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, DetalleReceta $detalleReceta)
    {
        $validated = $request->validate([
            'id_receta' => 'required|exists:recetas,id_receta',
            'id_insumo' => 'required|exists:insumos,id_insumo',
            'cantidad_requerida' => 'required|numeric|min:0.01',
        ]);

        // Verificar que no exista el mismo detalle en otra fila
        $existe = DetalleReceta::where('id_receta', $validated['id_receta'])
            ->where('id_insumo', $validated['id_insumo'])
            ->where('id_detalle_receta', '!=', $detalleReceta->id_detalle_receta)
            ->exists();

        if ($existe) {
            return back()->with('error', 'Este insumo ya está asociado a esta receta');
        }

        $detalleReceta->update($validated);

        return redirect()->route('detalles-receta.show', $detalleReceta)
            ->with('success', 'Detalle de receta actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(DetalleReceta $detalleReceta)
    {
        $detalleReceta->delete();

        return redirect()->route('detalles-receta.index')
            ->with('success', 'Detalle de receta eliminado correctamente');
    }

    /**
     * Filtrar por receta
     */
    public function porReceta($id_receta)
    {
        $receta = Receta::findOrFail($id_receta);
        $detalles = DetalleReceta::where('id_receta', $id_receta)
            ->with('insumo')
            ->paginate(15);

        return view('produccion.detalles-receta.index', compact('detalles', 'receta'));
    }
}
