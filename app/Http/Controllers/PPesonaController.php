<?php

namespace App\Http\Controllers;

use App\Models\Ppersona;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class PPesonaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $personas = Ppersona::with('proveedor')
            ->paginate(15);
        
        return view('ppersona.index', compact('personas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::where('tipo_proveedor', 'persona')->get();
        
        return view('ppersona.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'nombre' => 'required|string|max:255',
        ]);

        Ppersona::create($validated);

        return redirect()->route('ppersona.index')
            ->with('success', 'Persona proveedor creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Ppersona $ppersona)
    {
        $ppersona->load('proveedor');
        
        return view('ppersona.show', compact('ppersona'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Ppersona $ppersona)
    {
        $proveedores = Proveedor::where('tipo_proveedor', 'persona')->get();
        
        return view('ppersona.edit', compact('ppersona', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Ppersona $ppersona)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:255',
        ]);

        $ppersona->update($validated);

        return redirect()->route('ppersona.index')
            ->with('success', 'Persona proveedor actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Ppersona $ppersona)
    {
        $ppersona->delete();

        return redirect()->route('ppersona.index')
            ->with('success', 'Persona proveedor eliminada exitosamente');
    }
}
