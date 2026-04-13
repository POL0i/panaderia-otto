<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of clientes.
     */
    public function index()
    {
        $clientes = \App\Models\Cliente::all();
        return view('clientes.index', compact('clientes'));
    }

    /**
     * Show the form for creating a new cliente.
     */
    public function create()
    {
        return view('clientes.create');
    }

    /**
     * Store a newly created cliente in database.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|integer',
        ]);

        \App\Models\Cliente::create($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente creado exitosamente.');
    }

    /**
     * Show the form for editing the specified cliente.
     */
    public function edit($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        return view('clientes.edit', compact('cliente'));
    }

    /**
     * Update the specified cliente in database.
     */
    public function update(Request $request, $id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);

        $validated = $request->validate([
            'nombre' => 'required|string|max:25',
            'apellido' => 'required|string|max:25',
            'telefono' => 'required|integer',
        ]);

        $cliente->update($validated);

        return redirect()->route('clientes.index')->with('success', 'Cliente actualizado exitosamente.');
    }

    /**
     * Remove the specified cliente from database.
     */
    public function destroy($id)
    {
        $cliente = \App\Models\Cliente::findOrFail($id);
        $cliente->delete();

        return redirect()->route('clientes.index')->with('success', 'Cliente eliminado exitosamente.');
    }
}
