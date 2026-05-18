<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\Producto;
use App\Models\Cliente;
use App\Models\Produccion;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Estadísticas principales
        $totalProductos = Producto::count();
        $totalClientes = Cliente::count();
        
        // Ventas de hoy
        $ventasHoy = NotaVenta::whereDate('fecha_venta', today())->sum('monto_total') ?: 0;
        
        // Pedidos pendientes (notas de venta pendientes) - asumiendo campo estado
        $pedidosPendientes = NotaVenta::where('estado', 'pendiente')->count() ?: 0;
        
        // Ventas últimos 7 días (para gráfico)
        $ventasPorDia = $this->getVentasPorUltimosDias(7);
        
        // Productos más vendidos (últimos 30 días)
        $productosTop = $this->getProductosMasVendidos(5);
        
        // Notificaciones (actividad reciente basada en timestamps)
        $notificaciones = $this->getNotificacionesRecientes(10);
        
        // Actividad reciente (últimas acciones registradas en logs o en tablas)
        $actividades = $this->getActividadReciente(10);
        
        // Reporte de ventas por fechas (si el usuario envía rango)
        $ventasPorFechas = null;
        if ($request->has('fecha_inicio') && $request->has('fecha_fin')) {
            $ventasPorFechas = $this->getVentasPorRango($request->fecha_inicio, $request->fecha_fin);
        }
        
        return view('home', compact(
            'totalProductos', 'ventasHoy', 'totalClientes', 'pedidosPendientes',
            'ventasPorDia', 'productosTop', 'notificaciones', 'actividades', 'ventasPorFechas'
        ));
    }
    
    /**
     * Ventas agrupadas por día para un número de días atrás
     */
private function getVentasPorUltimosDias($dias = 7)
{
    $resultado = [];
    
    for ($i = $dias - 1; $i >= 0; $i--) {
        $fecha = now()->subDays($i)->toDateString();
        
        $total = NotaVenta::whereDate('fecha_venta', $fecha)
            ->where('estado', 'completado')
            ->sum('monto_total');
        
        // Asegurar que sea número (float) no string
        $resultado[] = (float) $total;
    }
    
    return $resultado;
}
    
    /**
     * Productos más vendidos por cantidad
     */
    private function getProductosMasVendidos($limite = 5)
    {
        return DB::table('detalles_venta')
            ->join('items', 'detalles_venta.id_item', '=', 'items.id_item')
            ->leftJoin('productos', 'items.id_item', '=', 'productos.id_item')
            ->select('items.nombre', DB::raw('SUM(detalles_venta.cantidad) as total_vendido'))
            ->where('detalles_venta.created_at', '>=', now()->subDays(30))
            ->groupBy('items.id_item', 'items.nombre')
            ->orderBy('total_vendido', 'desc')
            ->limit($limite)
            ->get();
    }
    
    /**
     * Notificaciones basadas en timestamps de tablas (nuevos productos, producciones, etc.)
     */
    private function getNotificacionesRecientes($limite = 10)
    {
        $notificaciones = collect();
        
        // Nuevos productos en las últimas 24 horas
        $nuevosProductos = Producto::where('created_at', '>=', now()->subHours(24))
            ->with('categoria')
            ->get()
            ->map(function($p) {
                return (object)[
                    'mensaje' => "Nuevo producto: {$p->nombre} (Categoría: " . ($p->categoria->nombre ?? 'Sin categoría') . ")",  // ← Punto y coma y coma
                    'fecha' => $p->created_at,  // ← Coma
                    'icono' => 'fas fa-box',    // ← Coma
                    'color' => '#8B4513'        // ← Último sin coma
                ];
            });
            
        // Nuevas producciones en las últimas 24 horas
        if (class_exists(\App\Models\Produccion::class)) {
            $nuevasProducciones = Produccion::where('created_at', '>=', now()->subHours(24))
                ->get()
                ->map(function($pro) {
                    $solicitante = $pro->empleadoSolicita->nombre ?? 'Desconocido';
                    return (object)[
                        'mensaje' => "Nueva producción solicitada por {$solicitante}: #{$pro->id_produccion}",
                        'fecha' => $pro->created_at,
                        'icono' => 'fas fa-industry',
                        'color' => '#A0522D'
                    ];
                });
            $notificaciones = $notificaciones->merge($nuevasProducciones);
        }
        
        // Nuevas notas de venta en las últimas 24 horas
        $nuevasVentas = NotaVenta::where('created_at', '>=', now()->subHours(24))
            ->with('cliente')
            ->get()
            ->map(function($nv) {
                return (object)[
                    'mensaje' => "Nueva venta #{$nv->id_nota_venta} - Cliente: {$nv->cliente->nombre} - Bs. {$nv->monto_total}",
                    'fecha' => $nv->created_at,
                    'icono' => 'fas fa-shopping-cart',
                    'color' => '#D2B48C'
                ];
            });
            
        $notificaciones = $notificaciones->merge($nuevosProductos)->merge($nuevasVentas);
        
        // Ordenar por fecha descendente y tomar límite
        $notificaciones = $notificaciones->sortByDesc('fecha')->take($limite);
        
        return $notificaciones;
    }
    
    /**
     * Actividad reciente (últimos usuarios logueados, acciones registradas)
     * Si no tienes logs, podemos mostrarla desde tablas con timestamps
     */
    private function getActividadReciente($limite = 10)
    {
        // Ejemplo: combinar los últimos registros de varias tablas
        $actividades = collect();
        
        // Últimos productos creados
        $ultimosProductos = Producto::orderBy('created_at', 'desc')->take(3)->get()
            ->map(function($p) {
                return (object)[
                    'tipo' => 'producto',
                    'accion' => 'Creación',
                    'descripcion' => "Producto: {$p->nombre}",
                    'usuario' => 'Sistema',
                    'fecha' => $p->created_at
                ];
            });
            
        // Últimas ventas
        $ultimasVentas = NotaVenta::orderBy('created_at', 'desc')->take(4)->get()
            ->map(function($nv) {
                return (object)[
                    'tipo' => 'venta',
                    'accion' => 'Registro',
                    'descripcion' => "Venta #{$nv->id_nota_venta} por Bs. {$nv->monto_total}",
                    'usuario' => $nv->empleado->nombre ?? 'Sistema',
                    'fecha' => $nv->created_at
                ];
            });
            
        // Últimas producciones
        if (class_exists(\App\Models\Produccion::class)) {
            $ultimasProducciones = Produccion::orderBy('created_at', 'desc')->take(3)->get()
                ->map(function($pro) {
                    return (object)[
                        'tipo' => 'produccion',
                        'accion' => 'Solicitud',
                        'descripcion' => "Producción #{$pro->id_produccion} - Estado: {$pro->estado}",
                        'usuario' => $pro->empleadoSolicita->nombre ?? 'Sistema',
                        'fecha' => $pro->created_at
                    ];
                });
            $actividades = $actividades->merge($ultimasProducciones);
        }
        
        $actividades = $actividades->merge($ultimosProductos)->merge($ultimasVentas);
        $actividades = $actividades->sortByDesc('fecha')->take($limite);
        
        return $actividades;
    }
    
    /**
     * Reporte de ventas por rango de fechas
     */
    private function getVentasPorRango($fechaInicio, $fechaFin)
    {
        return NotaVenta::whereBetween('fecha_venta', [$fechaInicio, $fechaFin])
            ->with('cliente', 'empleado')
            ->orderBy('fecha_venta', 'desc')
            ->paginate(15);
    }
}