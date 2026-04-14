<?php

namespace App\Http\Controllers;

use App\Models\Insumo;
use App\Models\CategoriaInsumo;
use App\Models\Item;
use Illuminate\Http\Request;

class InsumoController extends Controller
{
    public function index()
    {
        $insumos = Insumo::with(['categoria', 'item'])
            ->orderBy('nombre')
            ->paginate(15);
            
        $categorias = CategoriaInsumo::orderBy('nombre')->get();
        $items = Item::orderBy('nombre')->get();

        return view('produccion.insumos.index', compact('insumos', 'categorias', 'items'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'id_item' => 'nullable|exists:items,id_item',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        $insumo = Insumo::create($validated);

        return response()->json([
            'success' => true,
            'insumo' => $insumo->load('categoria'),
            'message' => 'Insumo creado exitosamente'
        ]);
    }

    public function update(Request $request, Insumo $insumo)
    {
        $validated = $request->validate([
            'nombre' => 'required|string|max:100',
            'id_cat_insumo' => 'required|exists:categoria_insumo,id_cat_insumo',
            'id_item' => 'nullable|exists:items,id_item',
            'precio_compra' => 'nullable|numeric|min:0',
        ]);

        $insumo->update($validated);

        return response()->json([
            'success' => true,
            'insumo' => $insumo->load('categoria'),
            'message' => 'Insumo actualizado'
        ]);
    }

    public function destroy(Insumo $insumo)
    {
        if ($insumo->detallesReceta()->count() > 0 || $insumo->detallesCompra()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar un insumo con recetas o compras asociadas'
            ], 400);
        }

        $insumo->delete();

        return response()->json([
            'success' => true,
            'message' => 'Insumo eliminado'
        ]);
    }
}