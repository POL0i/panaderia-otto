<?php

namespace App\Http\Controllers;

use App\Models\DetalleCompra;
use App\Models\NotaCompra;
use App\Models\Almacen;
use App\Models\Item;
use App\Models\AlmacenItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetalleCompraController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $detallesCompra = DetalleCompra::with(['notaCompra', 'almacen', 'item'])
            ->paginate(15);
        
        return view('detallecompra.index', compact('detallesCompra'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $notaCompraId = $request->get('nota_compra_id');
        $notaCompra = null;
        
        if ($notaCompraId) {
            $notaCompra = NotaCompra::findOrFail($notaCompraId);
        }
        
        $notasCompra = NotaCompra::where('estado', 'pendiente')->get();
        $almacenes = Almacen::all();
        
        // Obtener solo items que sean insumos
        $itemsInsumo = Item::whereHas('insumo')->with('insumo')->get();
        
        return view('detallecompra.create', compact('notasCompra', 'almacenes', 'itemsInsumo', 'notaCompra'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_nota_compra' => 'required|exists:notas_compra,id_nota_compra',
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => [
                'required',
                'exists:items,id_item',
                function ($attribute, $value, $fail) {
                    $item = Item::find($value);
                    if (!$item || !$item->insumo) {
                        $fail('El item seleccionado debe ser un insumo.');
                    }
                },
            ],
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $detalleCompra = DetalleCompra::create($validated);
            
            // Actualizar monto total de la nota de compra
            $this->actualizarTotalNotaCompra($detalleCompra->id_nota_compra);
            
            DB::commit();
            
            return redirect()->route('notas-compra.show', $detalleCompra->id_nota_compra)
                ->with('success', 'Detalle de compra agregado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al crear detalle: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($notaCompra, $almacen, $item)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->with(['notaCompra', 'almacen', 'item.insumo'])
            ->firstOrFail();
        
        return view('detallecompra.show', compact('detalleCompra'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($notaCompra, $almacen, $item)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();
        
        $almacenes = Almacen::all();
        $itemsInsumo = Item::whereHas('insumo')->with('insumo')->get();
        
        return view('detallecompra.edit', compact('detalleCompra', 'almacenes', 'itemsInsumo'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $notaCompra, $almacen, $item)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        $validated = $request->validate([
            'cantidad' => 'required|integer|min:1',
            'precio' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $detalleCompra->update($validated);
            $this->actualizarTotalNotaCompra($detalleCompra->id_nota_compra);
            
            DB::commit();
            
            return redirect()->route('notas-compra.show', $detalleCompra->id_nota_compra)
                ->with('success', 'Detalle de compra actualizado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al actualizar: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($notaCompra, $almacen, $item)
    {
        $detalleCompra = DetalleCompra::where('id_nota_compra', $notaCompra)
            ->where('id_almacen', $almacen)
            ->where('id_item', $item)
            ->firstOrFail();

        try {
            DB::beginTransaction();
            
            $idNotaCompra = $detalleCompra->id_nota_compra;
            $detalleCompra->delete();
            $this->actualizarTotalNotaCompra($idNotaCompra);
            
            DB::commit();
            
            return redirect()->route('notas-compra.show', $idNotaCompra)
                ->with('success', 'Detalle de compra eliminado exitosamente');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
        }
    }
    
    /**
     * Actualizar el monto total de la nota de compra
     */
    private function actualizarTotalNotaCompra($idNotaCompra)
    {
        $total = DetalleCompra::where('id_nota_compra', $idNotaCompra)
            ->sum(DB::raw('cantidad * precio'));
            
        NotaCompra::where('id_nota_compra', $idNotaCompra)
            ->update(['monto_total' => $total]);
    }
    
    /**
     * Obtener stock disponible de un insumo en un almacén específico
     */
    public function getStock(Request $request)
    {
        $request->validate([
            'id_almacen' => 'required|exists:almacenes,id_almacen',
            'id_item' => 'required|exists:items,id_item',
        ]);
        
        $almacenItem = AlmacenItem::where('id_almacen', $request->id_almacen)
            ->where('id_item', $request->id_item)
            ->first();
            
        return response()->json([
            'stock' => $almacenItem ? $almacenItem->stock : 0
        ]);
    }
}