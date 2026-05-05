<?php

namespace App\Http\Controllers;

use App\Models\Traspaso;
use App\Models\TraspasoAlmacenItem;
use App\Models\Almacen;
use App\Models\Item;
use App\Models\AlmacenItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class TraspasoInventarioController extends Controller
{
    public function index()
    {
        $traspasos = Traspaso::with(['empleado', 'detalles'])
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('inventario.traspasos.index', compact('traspasos'));
    }

    public function create()
    {
        $almacenes = Almacen::all();
        $items = Item::with(['producto', 'insumo'])->get();

        return view('inventario.traspasos.create', compact('almacenes', 'items'));
    }

   public function store(Request $request)
{
    $validated = $request->validate([
        'id_almacen_origen' => 'required|exists:almacenes,id_almacen',
        'id_almacen_destino' => 'required|different:id_almacen_origen|exists:almacenes,id_almacen',
        'descripcion' => 'nullable|string',
        'detalles' => 'required|array|min:1',
        'detalles.*.id_item' => 'required|exists:items,id_item',
        'detalles.*.cantidad' => 'required|integer|min:1',
    ]);

    DB::beginTransaction();
    try {
        $traspaso = Traspaso::create([
            'fecha_traspaso' => now(),
            'descripcion' => $validated['descripcion'] ?? null,
            'id_empleado' => Auth::user()->empleado->id_empleado ?? 1,
        ]);

        foreach ($validated['detalles'] as $detalle) {
            $idItem = $detalle['id_item'];
            $cantidad = $detalle['cantidad'];

            $existeOrigen = DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_origen'])
                ->where('id_item', $idItem)
                ->exists();

            if (!$existeOrigen) {
                DB::table('almacen_item')->insert([
                    'id_almacen' => $validated['id_almacen_origen'],
                    'id_item' => $idItem,
                    'stock' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Asegurar que existe en almacen_item (destino)
            $existeDestino = DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_destino'])
                ->where('id_item', $idItem)
                ->exists();

            if (!$existeDestino) {
                DB::table('almacen_item')->insert([
                    'id_almacen' => $validated['id_almacen_destino'],
                    'id_item' => $idItem,
                    'stock' => 0,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            // Validar stock
            $stockOrigen = DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_origen'])
                ->where('id_item', $idItem)
                ->value('stock') ?? 0;

            if ($stockOrigen < $cantidad) {
                $item = Item::find($idItem);
                throw new \Exception("Stock insuficiente de '{$item->nombre}'. Disponible: {$stockOrigen}");
            }

            TraspasoAlmacenItem::create([
                'id_traspaso' => $traspaso->id_traspaso,
                'id_almacen_origen' => $validated['id_almacen_origen'],
                'id_almacen_destino' => $validated['id_almacen_destino'],
                'id_item' => $idItem,
                'cantidad' => $cantidad,
            ]);
        }

        DB::commit();

        return redirect()->route('traspasos.show', $traspaso)
            ->with('success', 'Traspaso creado correctamente');
    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with('error', $e->getMessage())->withInput();
    }
}
public function show(Traspaso $traspaso)
{
    $traspaso->load(['empleado', 'detalles']);
    
    return view('inventario.traspasos.show', compact('traspaso'));
}

    public function destroy(Traspaso $traspaso)
    {
        // El modelo TraspasoAlmacenItem tiene booted() que revierte el stock al eliminar
        $traspaso->detalles()->delete();
        $traspaso->delete();

        return redirect()->route('traspasos.index')
            ->with('success', 'Traspaso eliminado y stock revertido');
    }
}