<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PedidoClienteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

public function index()
{
    $usuario = Auth::user();
    
    $clienteId = null;
    if ($usuario->cliente) {
        $clienteId = $usuario->cliente->id_cliente;
    } elseif ($usuario->empleado) {
        $empleado = $usuario->empleado;
        $cliente = \App\Models\Cliente::where('nombre', $empleado->nombre)
            ->where('apellido', $empleado->apellido ?? '')
            ->first();
        if ($cliente) {
            $clienteId = $cliente->id_cliente;
        }
    }
    
    if (!$clienteId) {
        return redirect()->route('landing')->with('info', 'Realiza tu primera compra para ver tus pedidos.');
    }

    $pedidos = NotaVenta::with(['detalles.item', 'transaccionLibelula'])
        ->where('id_cliente', $clienteId)
        ->orderBy('created_at', 'desc')
        ->get();

    return view('mis-pedidos', compact('pedidos'));
}

    public function cancelar($id)
    {
        $usuario = Auth::user();
        
        // Obtener cliente ID igual que en index
        $clienteId = null;
        
        if ($usuario->cliente) {
            $clienteId = $usuario->cliente->id_cliente;
        } elseif ($usuario->empleado) {
            $empleado = $usuario->empleado;
            $cliente = \App\Models\Cliente::where('nombre', $empleado->nombre)
                ->where('apellido', $empleado->apellido ?? '')
                ->first();
            if ($cliente) {
                $clienteId = $cliente->id_cliente;
            }
        }

        if (!$clienteId) {
            return redirect()->route('mis-pedidos')->with('error', 'No se pudo identificar tu cuenta de cliente.');
        }

        $pedido = NotaVenta::where('id_cliente', $clienteId)
            ->where('id_nota_venta', $id)
            ->where('estado', 'pendiente')
            ->first();

        if (!$pedido) {
            return redirect()->route('mis-pedidos')->with('error', 'Pedido no encontrado.');
        }

        $pedido->estado = 'cancelado';
        $pedido->save();

        return redirect()->route('mis-pedidos')->with('success', 'Pedido cancelado exitosamente.');
    }
}