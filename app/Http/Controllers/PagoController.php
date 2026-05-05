<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransaccionLibelula;
use Illuminate\Support\Facades\Log;
use App\services\LibelulaService;

class PagoController extends Controller
{
    public function webhookPagoExitoso(Request $request)
    {
        // Libélula envía: transaction_id, invoice_id, invoice_url
        $transactionId = $request->get('transaction_id');
        
        $transaccion = TransaccionLibelula::where('id_transaccion_libelula', $transactionId)->first();
        
        if (!$transaccion) {
            Log::warning('Webhook: Transacción no encontrada', ['transaction_id' => $transactionId]);
            return response()->json(['error' => 'Transacción no encontrada'], 404);
        }
        
        // Actualizar estado
        $transaccion->update(['estado' => 'pagado']);
        
        // Actualizar pedido
        $pedido = $transaccion->pedido;
        $pedido->update(['estado' => 'pagado', 'fecha_pago' => now()]);
        
        // Limpiar carrito
        session()->forget('cart');
        
        Log::info('Pago confirmado', ['pedido_id' => $pedido->id]);
        
        return response()->json(['success' => true]);
    }
}
