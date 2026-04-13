<?php

namespace App\Http\Controllers;

use App\Models\ConfiguracionInventario;
use Illuminate\Http\Request;

class ConfiguracionInventarioController extends Controller
{
    /**
     * Show the form for editing the resource.
     */
    public function edit()
    {
        $config = ConfiguracionInventario::obtener();
        return view('inventario.configuracion.edit', compact('config'));
    }

    /**
     * Update the resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'metodo_valuacion_predeterminado' => 'required|in:PEPS,UEPS',
            'automatizar_movimientos' => 'required|boolean',
            'requerir_aprobacion' => 'required|boolean',
        ]);

        $config = ConfiguracionInventario::obtener();
        $config->update($validated);

        return redirect()->route('configuracion.edit')
            ->with('success', 'Configuración actualizada correctamente');
    }
}
