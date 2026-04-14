<?php

namespace App\Http\Controllers;

use App\Models\CategoriaInsumo;
use Illuminate\Http\Request;

class CategoriaInsumoController extends Controller
{
    public function index()
    {
        $categorias = CategoriaInsumo::withCount('insumos')->paginate(15);
        return view('produccion.categorias-insumo.index', compact('categorias'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria_insumo,nombre',
            'descripcion' => 'nullable|string',
        ]);

        $categoria = CategoriaInsumo::create($validated);

        return response()->json([
            'success' => true,
            'categoria' => $categoria,
            'message' => 'Categoría creada exitosamente'
        ]);
    }

    public function update(Request $request, CategoriaInsumo $categoria)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100|unique:categoria_insumo,nombre,' . $categoria->id_cat_insumo . ',id_cat_insumo',
            'descripcion' => 'nullable|string',
        ]);

        $categoria->update($validated);

        return response()->json([
            'success' => true,
            'categoria' => $categoria,
            'message' => 'Categoría actualizada'
        ]);
    }

    public function destroy(CategoriaInsumo $categoria)
    {
        if ($categoria->insumos()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar una categoría con insumos asociados'
            ], 400);
        }

        $categoria->delete();

        return response()->json([
            'success' => true,
            'message' => 'Categoría eliminada'
        ]);
    }
}