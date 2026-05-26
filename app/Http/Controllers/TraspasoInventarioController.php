<?php

namespace App\Http\Controllers;

use App\Models\Traspaso;
use App\Models\TraspasoAlmacenItem;
use App\Models\Almacen;
use App\Models\Item;
use App\Models\AlmacenItem;
use App\Models\LoteInventario;
use App\Models\MovimientoInventario;
use App\Models\ConfiguracionInventario;
use Illuminate\Support\Facades\Log;
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
        $metodoValuacion = ConfiguracionInventario::obtener()->metodo_valuacion_predeterminado ?? 'PEPS';

        $traspaso = Traspaso::create([
            'fecha_traspaso' => now(),
            'descripcion' => $validated['descripcion'] ?? null,
            'id_empleado' => Auth::user()->empleado->id_empleado ?? 1,
        ]);

        foreach ($validated['detalles'] as $detalle) {
            $idItem = $detalle['id_item'];
            $cantidad = $detalle['cantidad'];

            // Verificar stock
            $stockOrigen = DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_origen'])
                ->where('id_item', $idItem)
                ->value('stock') ?? 0;

            if ($stockOrigen < $cantidad) {
                $item = Item::find($idItem);
                throw new \Exception("Stock insuficiente de '{$item->nombre}'. Disponible: {$stockOrigen}, Requerido: {$cantidad}");
            }

            // 1. Crear detalle del traspaso
            TraspasoAlmacenItem::create([
                'id_traspaso' => $traspaso->id_traspaso,
                'id_almacen_origen' => $validated['id_almacen_origen'],
                'id_almacen_destino' => $validated['id_almacen_destino'],
                'id_item' => $idItem,
                'cantidad' => $cantidad,
            ]);

            // 2. Actualizar stock en almacen_item (origen: decrementar)
            DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_origen'])
                ->where('id_item', $idItem)
                ->decrement('stock', $cantidad);

            // 3. Asegurar que existe registro en destino
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

            // 4. Incrementar stock en destino
            DB::table('almacen_item')
                ->where('id_almacen', $validated['id_almacen_destino'])
                ->where('id_item', $idItem)
                ->increment('stock', $cantidad);

            // 5. ✅ CONSUMIR LOTES DEL ORIGEN (PEPS)
            $resultado = LoteInventario::consumir(
                $validated['id_almacen_origen'],
                $idItem,
                $cantidad,
                $metodoValuacion,
                $traspaso->id_traspaso,
                'traspaso'
            );

            Log::info('Lotes consumidos en origen', [
                'cantidad_consumida' => $resultado['cantidad_consumida'],
                'lotes_usados' => $resultado['lotes_usados']
            ]);

            // 6. ✅ CREAR NUEVOS LOTES EN DESTINO (con los mismos datos)
            Log::info('📦 Estructura de lotes_usados:', $resultado['lotes_usados']);
            foreach ($resultado['lotes_usados'] as $loteUsado) {
                Log::info('📦 Datos del lote a crear en destino:', [
                    'id_almacen' => $validated['id_almacen_destino'],
                    'id_item' => $idItem,
                    'cantidad_inicial' => $loteUsado['cantidad'],
                    'precio_unitario' => $loteUsado['precio_unitario'],
                    'fecha_entrada' => $loteUsado['fecha_entrada'],
                    'referencia_tipo' => 'traspaso'
                ]);

                try {
                    LoteInventario::create([
                        'id_almacen' => $validated['id_almacen_destino'],
                        'id_item' => $idItem,
                        'cantidad_inicial' => $loteUsado['cantidad'],
                        'cantidad_disponible' => $loteUsado['cantidad'],
                        'precio_unitario' => $loteUsado['precio_unitario'],
                        'fecha_entrada' => $loteUsado['fecha_entrada'],
                        'fecha_vencimiento' => $loteUsado['fecha_vencimiento'] ?? null,
                        'metodo_valuacion' => $loteUsado['metodo_valuacion'] ?? $metodoValuacion,
                        'estado' => 'disponible',
                        'referencia_id' => $traspaso->id_traspaso,
                        'referencia_tipo' => 'traspaso',
                    ]);
                    Log::info('✅ Lote creado exitosamente');
                } catch (\Exception $e) {
                    Log::error('❌ Error al crear lote: ' . $e->getMessage());
                    throw $e;
                }
            }

            // 7. Registrar movimiento de inventario (egreso en origen)
            MovimientoInventario::registrar([
                'tipo_movimiento' => 'traspaso_origen',
                'id_almacen' => $validated['id_almacen_origen'],
                'id_item' => $idItem,
                'cantidad' => -$cantidad,
                'precio_unitario' => $resultado['costo_unitario_promedio'],
                'costo_total' => -$resultado['costo_total'],
                'referencia_id' => $traspaso->id_traspaso,
                'referencia_tipo' => 'traspaso',
                'observaciones' => "Traspaso #{$traspaso->id_traspaso} hacia almacén {$validated['id_almacen_destino']}",
            ]);

            // 8. Registrar movimiento de inventario (ingreso en destino)
            MovimientoInventario::registrar([
                'tipo_movimiento' => 'traspaso_destino',
                'id_almacen' => $validated['id_almacen_destino'],
                'id_item' => $idItem,
                'cantidad' => $cantidad,
                'precio_unitario' => $resultado['costo_unitario_promedio'],
                'costo_total' => $resultado['costo_total'],
                'referencia_id' => $traspaso->id_traspaso,
                'referencia_tipo' => 'traspaso',
                'observaciones' => "Traspaso #{$traspaso->id_traspaso} desde almacén {$validated['id_almacen_origen']}",
            ]);
        }

        DB::commit();

        return redirect()->route('traspasos.show', $traspaso)
            ->with('success', 'Traspaso creado correctamente con actualización de lotes.');

    } catch (\Exception $e) {
        DB::rollBack();
        Log::error('Error al crear traspaso: ' . $e->getMessage());
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

    public function getStock(Request $request)
{
    $request->validate([
        'id_almacen' => 'required|exists:almacenes,id_almacen',
        'id_item' => 'required|exists:items,id_item',
    ]);

    $stock = AlmacenItem::where('id_almacen', $request->id_almacen)
        ->where('id_item', $request->id_item)
        ->value('stock') ?? 0;

    $item = Item::find($request->id_item);
    $unidad = $item->unidad_medida ?? 'unidad';

    return response()->json([
        'success' => true,
        'stock' => $stock,
        'unidad' => $unidad,
        'item_nombre' => $item->nombre,
    ]);
}

/**
 * Obtener la capacidad disponible de un almacén (para AJAX)
 */
public function getCapacidadDisponible(Request $request)
{
    $request->validate([
        'id_almacen' => 'required|exists:almacenes,id_almacen',
    ]);

    $almacen = Almacen::find($request->id_almacen);
    $stockActual = AlmacenItem::where('id_almacen', $request->id_almacen)->sum('stock');
    $capacidad = $almacen->capacidad; // puede ser null (sin límite)
    $disponible = $capacidad ? $capacidad - $stockActual : null;

    return response()->json([
        'success' => true,
        'stock_actual' => $stockActual,
        'capacidad' => $capacidad,
        'disponible' => $disponible,
        'sin_limite' => is_null($capacidad),
    ]);
}

}