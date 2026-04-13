<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use App\Models\Ppersona;
use App\Models\Pempresa;
use Illuminate\Http\Request;

class ProveedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $proveedores = Proveedor::with(['persona', 'empresa'])
            ->paginate(15);
        
        return view('proveedores.index', compact('proveedores'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('proveedores.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'tipo_proveedor' => 'required|in:persona,empresa',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'nombre' => 'nullable|string|max:255',
            'razon_social' => 'nullable|string|max:255',
        ]);

        $proveedor = Proveedor::create([
            'tipo_proveedor' => $validated['tipo_proveedor'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
            'correo' => $validated['correo'],
        ]);

        if ($validated['tipo_proveedor'] === 'persona' && $validated['nombre']) {
            Ppersona::create([
                'id_proveedor' => $proveedor->id_proveedor,
                'nombre' => $validated['nombre'],
            ]);
        } elseif ($validated['tipo_proveedor'] === 'empresa' && $validated['razon_social']) {
            Pempresa::create([
                'id_proveedor' => $proveedor->id_proveedor,
                'razon_social' => $validated['razon_social'],
            ]);
        }

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor creado exitosamente');
    }

    /**
     * Display the specified resource.
     */
    public function show(Proveedor $proveedor)
    {
        $proveedor->load(['persona', 'empresa', 'notasCompra']);
        
        return view('proveedores.show', compact('proveedor'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Proveedor $proveedor)
    {
        $proveedor->load(['persona', 'empresa']);
        
        return view('proveedores.edit', compact('proveedor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Proveedor $proveedor)
    {
        $validated = $request->validate([
            'tipo_proveedor' => 'required|in:persona,empresa',
            'telefono' => 'required|string|max:20',
            'direccion' => 'required|string|max:255',
            'correo' => 'required|email|max:255',
            'nombre' => 'nullable|string|max:255',
            'razon_social' => 'nullable|string|max:255',
        ]);

        $proveedor->update([
            'tipo_proveedor' => $validated['tipo_proveedor'],
            'telefono' => $validated['telefono'],
            'direccion' => $validated['direccion'],
            'correo' => $validated['correo'],
        ]);

        // Actualizar datos de persona
        if ($validated['tipo_proveedor'] === 'persona') {
            // Eliminar empresa si existe
            Pempresa::where('id_proveedor', $proveedor->id_proveedor)->delete();
            
            // Actualizar o crear persona
            if ($proveedor->persona) {
                $proveedor->persona->update(['nombre' => $validated['nombre']]);
            } elseif ($validated['nombre']) {
                Ppersona::create([
                    'id_proveedor' => $proveedor->id_proveedor,
                    'nombre' => $validated['nombre'],
                ]);
            }
        } elseif ($validated['tipo_proveedor'] === 'empresa') {
            // Eliminar persona si existe
            Ppersona::where('id_proveedor', $proveedor->id_proveedor)->delete();
            
            // Actualizar o crear empresa
            if ($proveedor->empresa) {
                $proveedor->empresa->update(['razon_social' => $validated['razon_social']]);
            } elseif ($validated['razon_social']) {
                Pempresa::create([
                    'id_proveedor' => $proveedor->id_proveedor,
                    'razon_social' => $validated['razon_social'],
                ]);
            }
        }

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor actualizado exitosamente');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Proveedor $proveedor)
    {
        // Eliminar datos relacionados
        Ppersona::where('id_proveedor', $proveedor->id_proveedor)->delete();
        Pempresa::where('id_proveedor', $proveedor->id_proveedor)->delete();
        
        $proveedor->delete();

        return redirect()->route('proveedores.index')
            ->with('success', 'Proveedor eliminado exitosamente');
    }
}
