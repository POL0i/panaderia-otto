<?php

namespace App\Http\Controllers;

use App\Models\Pempresa;
use App\Models\Proveedor;
use Illuminate\Http\Request;

class PempresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $empresas = Pempresa::with('proveedor')
            ->paginate(15);
        
        return view('pempresa.index', compact('empresas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $proveedores = Proveedor::where('tipo_proveedor', 'empresa')->get();
        
        return view('pempresa.create', compact('proveedores'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_proveedor' => 'required|exists:proveedores,id_proveedor',
            'razon_social' => 'required|string|max:255',
        ]);

        Pempresa::create($validated);

        return redirect()->route('pempresa.index')
            ->with('success', 'Empresa proveedor creada exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pempresa $pempresa)
    {
        $pempresa->load('proveedor');
        
        return view('pempresa.show', compact('pempresa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pempresa $pempresa)
    {
        $proveedores = Proveedor::where('tipo_proveedor', 'empresa')->get();
        
        return view('pempresa.edit', compact('pempresa', 'proveedores'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pempresa $pempresa)
    {
        $validated = $request->validate([
            'razon_social' => 'required|string|max:255',
        ]);

        $pempresa->update($validated);

        return redirect()->route('pempresa.index')
            ->with('success', 'Empresa proveedor actualizada exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pempresa $pempresa)
    {
        $pempresa->delete();

        return redirect()->route('pempresa.index')
            ->with('success', 'Empresa proveedor eliminada exitosamente');
    }
}
