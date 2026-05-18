<?php

namespace App\Http\Controllers;

use App\Models\NotaVenta;
use App\Models\NotaCompra;
use App\Models\Produccion;
use App\Models\LoteInventario;
use App\Models\DetalleProduccion;
use App\Models\MovimientoInventario;
use App\Models\Item;
use App\Models\Proveedor;
use App\Models\Almacen;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Services\MailService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteController extends Controller
{
    /**
     * Página principal de reportes
     */
    public function index()
    {
        // Lotes con stock bajo (menos del 30% de la cantidad inicial)
        $lotesBajos = LoteInventario::where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0)
            ->whereRaw('cantidad_disponible <= cantidad_inicial * 0.3')
            ->orderBy('cantidad_disponible', 'asc')
            ->limit(10)
            ->get();

        // Lotes próximos a vencer (7 días)
        $lotesPorVencer = LoteInventario::where('estado', 'disponible')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(7)])
            ->orderBy('fecha_vencimiento', 'asc')
            ->limit(10)
            ->get();

        // Lotes vencidos
        $lotesVencidos = LoteInventario::where('estado', 'disponible')
            ->whereNotNull('fecha_vencimiento')
            ->where('fecha_vencimiento', '<', now())
            ->orderBy('fecha_vencimiento', 'asc')
            ->limit(10)
            ->get();

        return view('reportes.index', compact('lotesBajos', 'lotesPorVencer', 'lotesVencidos'));
    }

    /**
     * Reporte de ventas por rango de fechas (AJAX)
     */
    public function ventas(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'required|date|after_or_equal:fecha_inicio',
        ]);

        $inicio = $request->fecha_inicio;
        $fin = $request->fecha_fin;

        $ventas = NotaVenta::whereBetween('fecha_venta', [$inicio, $fin])
            ->where('estado', 'completado')
            ->with('cliente', 'empleado')
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $total = $ventas->sum('monto_total');
        $count = $ventas->count();

        // Datos para gráfico (ventas por día)
        $ventasPorDia = NotaVenta::whereBetween('fecha_venta', [$inicio, $fin])
            ->where('estado', 'completado')
            ->select(DB::raw('DATE(fecha_venta) as fecha'), DB::raw('SUM(monto_total) as total'))
            ->groupBy('fecha')
            ->orderBy('fecha')
            ->get()
            ->mapWithKeys(fn($v) => [$v->fecha => $v->total]);

        if ($request->ajax()) {
            return response()->json([
                'ventas' => $ventas,
                'total' => $total,
                'count' => $count,
                'ventasPorDia' => $ventasPorDia,
            ]);
        }

        return view('reportes.ventas', compact('ventas', 'total', 'count', 'inicio', 'fin', 'ventasPorDia'));
    }

    /**
     * Reporte de inventario (lotes bajos, vencidos, etc.) - filtro por almacén
     */
       public function comercial(Request $request)
    {
        $inicio = $request->fecha_inicio ?? now()->subDays(30)->toDateString();
        $fin = $request->fecha_fin ?? now()->toDateString();

        // Ventas en el rango
        $ventas = NotaVenta::whereBetween('fecha_venta', [$inicio, $fin])
            ->where('estado', 'completado')
            ->with('cliente', 'empleado')
            ->orderBy('fecha_venta', 'desc')
            ->get();

        $totalVentas = $ventas->sum('monto_total');
        $countVentas = $ventas->count();

        // Compras en el rango
        $compras = NotaCompra::whereBetween('fecha_compra', [$inicio, $fin])
            ->where('estado', 'completado')
            ->with('proveedor', 'empleado')
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $totalCompras = $compras->sum('monto_total');
        $countCompras = $compras->count();

        // Datos para gráfico combinado
        $dias = [];
        $ventasPorDia = [];
        $comprasPorDia = [];
        for ($d = $inicio; $d <= $fin; $d = date('Y-m-d', strtotime($d . ' +1 day'))) {
            $dias[] = $d;
            $ventasPorDia[] = NotaVenta::whereDate('fecha_venta', $d)
                ->where('estado', 'completado')
                ->sum('monto_total');
            $comprasPorDia[] = NotaCompra::whereDate('fecha_compra', $d)
                ->where('estado', 'completado')
                ->sum('monto_total');
        }

        return view('reportes.comercial', compact(
            'inicio', 'fin',
            'ventas', 'totalVentas', 'countVentas',
            'compras', 'totalCompras', 'countCompras',
            'dias', 'ventasPorDia', 'comprasPorDia'
        ));
    }

    /**
     * Reporte de Inventario y Producción (insumos más usados, lotes bajos, vencimientos)
     */
    public function inventario()
    {
        // Insumos más usados en producciones (últimos 30 días)
        $insumosMasUsados = DetalleProduccion::where('tipo_movimiento', 'egreso')
            ->join('items', 'detalle_produccion.id_item', '=', 'items.id_item')
            ->select(
                'items.nombre',
                DB::raw('SUM(detalle_produccion.cantidad) as total_consumido')
            )
            ->groupBy('items.id_item', 'items.nombre')
            ->orderBy('total_consumido', 'desc')
            ->limit(10)
            ->get();

        // Máximo para calcular porcentajes de barras
        $maxConsumo = $insumosMasUsados->max('total_consumido') ?? 1;

        // Lotes con stock bajo (< 30%)
        $lotesBajos = LoteInventario::where('estado', 'disponible')
            ->where('cantidad_disponible', '>', 0)
            ->whereRaw('cantidad_disponible <= cantidad_inicial * 0.3')
            ->orderBy('cantidad_disponible', 'asc')
            ->limit(10)
            ->get();

        // Lotes próximos a vencer (7 días)
        $lotesPorVencer = LoteInventario::where('estado', 'disponible')
            ->whereNotNull('fecha_vencimiento')
            ->whereBetween('fecha_vencimiento', [now(), now()->addDays(7)])
            ->orderBy('fecha_vencimiento', 'asc')
            ->limit(10)
            ->get();

        return view('reportes.inventario', compact(
            'insumosMasUsados', 'maxConsumo',
            'lotesBajos', 'lotesPorVencer'
        ));
    }

    /**
     * Reporte de producción por rango de fechas
     */
    /**
 * Reporte de producción (productos más creados e insumos más usados)
 */
    public function produccion(Request $request)
    {
        // Productos más creados (últimos 30 días o todos)
        $productosMasCreados = DetalleProduccion::where('tipo_movimiento', 'ingreso')
            ->join('items', 'detalle_produccion.id_item', '=', 'items.id_item')
            ->select(
                'items.nombre',
                DB::raw('SUM(detalle_produccion.cantidad) as total_producido'),
                DB::raw('COUNT(DISTINCT detalle_produccion.id_produccion) as total_ordenes')
            )
            ->groupBy('items.id_item', 'items.nombre')
            ->orderBy('total_producido', 'desc')
            ->limit(10)
            ->get();

        $maxProducido = $productosMasCreados->max('total_producido') ?? 1;

        // Insumos más usados en producciones
        $insumosMasUsados = DetalleProduccion::where('tipo_movimiento', 'egreso')
            ->join('items', 'detalle_produccion.id_item', '=', 'items.id_item')
            ->select(
                'items.nombre',
                DB::raw('SUM(detalle_produccion.cantidad) as total_consumido'),
                DB::raw('COUNT(DISTINCT detalle_produccion.id_produccion) as total_ordenes')
            )
            ->groupBy('items.id_item', 'items.nombre')
            ->orderBy('total_consumido', 'desc')
            ->limit(10)
            ->get();

        $maxConsumo = $insumosMasUsados->max('total_consumido') ?? 1;

        // Últimas 10 producciones (para referencia rápida)
        $ultimasProducciones = Produccion::where('estado', 'aprobado')
            ->with('detalles.item', 'empleadoSolicita')
            ->orderBy('id_produccion', 'desc')
            ->limit(10)
            ->get();

        return view('reportes.produccion', compact(
            'productosMasCreados', 'maxProducido',
            'insumosMasUsados', 'maxConsumo',
            'ultimasProducciones'
        ));
    }

    /**
     * Reporte de compras por rango de fechas
     */
    public function compras(Request $request)
    {
        $request->validate([
            'fecha_inicio' => 'nullable|date',
            'fecha_fin' => 'nullable|date|after_or_equal:fecha_inicio',
        ]);

        $inicio = $request->fecha_inicio ?? now()->subDays(30)->toDateString();
        $fin = $request->fecha_fin ?? now()->toDateString();

        $compras = NotaCompra::whereBetween('fecha_compra', [$inicio, $fin])
            ->where('estado', 'completado')
            ->with('proveedor', 'empleado')
            ->orderBy('fecha_compra', 'desc')
            ->get();

        $total = $compras->sum('monto_total');
        $count = $compras->count();

        return view('reportes.compras', compact('compras', 'total', 'count', 'inicio', 'fin'));
    }
    public function enviarPDF(Request $request)
    {
        $request->validate([
            'correo' => 'required|email',
            'tipo' => 'required|in:inventario,produccion,comercial',
        ]);

        try {
            $correo = $request->correo;
            $tipo = $request->tipo;

            // Generar datos según el tipo
            switch ($tipo) {
                case 'inventario':
                    $data = $this->getDatosInventario();
                    $pdf = Pdf::loadView('reportes.pdf.inventario', $data);
                    $asunto = 'Reporte de Inventario - Panadería Otto';
                    $nombreArchivo = 'reporte-inventario.pdf';
                    break;
                case 'produccion':
                    $data = $this->getDatosProduccion();
                    $pdf = Pdf::loadView('reportes.pdf.produccion', $data);
                    $asunto = 'Reporte de Producción - Panadería Otto';
                    $nombreArchivo = 'reporte-produccion.pdf';
                    break;
                case 'comercial':
                    $data = $this->getDatosComercial($request);
                    $pdf = Pdf::loadView('reportes.pdf.comercial', $data);
                    $asunto = 'Reporte Comercial - Panadería Otto';
                    $nombreArchivo = 'reporte-comercial.pdf';
                    break;
            }

            // Guardar PDF temporalmente
            $pdfPath = storage_path('app/temp/' . $nombreArchivo);
            // Guardar PDF temporalmente
            $tempDir = storage_path('app/temp');
            if (!is_dir($tempDir)) {
                mkdir($tempDir, 0755, true);
            }
            $pdfPath = $tempDir . '/' . $nombreArchivo;
            $pdf->save($pdfPath);

            // Enviar correo
            $mailService = new MailService();
            $body = "<h2>Reporte de {$tipo}</h2><p>Adjunto encontrarás el reporte solicitado.</p>";
            $enviado = $mailService->sendEmail($correo, $asunto, $body, [$pdfPath]);

            // Eliminar PDF temporal
            unlink($pdfPath);

            if ($enviado) {
                return response()->json(['success' => true, 'message' => 'Reporte enviado exitosamente']);
            } else {
                throw new \Exception('Error al enviar el correo');
            }

        } catch (\Exception $e) {
            \Log::error('Error al generar/enviar PDF: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function getDatosInventario()
    {
        return [
            'lotesBajos' => LoteInventario::where('estado', 'disponible')
                ->where('cantidad_disponible', '>', 0)
                ->whereRaw('cantidad_disponible <= cantidad_inicial * 0.3')
                ->orderBy('cantidad_disponible', 'asc')
                ->limit(10)->get(),
            'lotesPorVencer' => LoteInventario::where('estado', 'disponible')
                ->whereNotNull('fecha_vencimiento')
                ->whereBetween('fecha_vencimiento', [now(), now()->addDays(7)])
                ->orderBy('fecha_vencimiento', 'asc')->get(),
            'movimientos' => MovimientoInventario::orderBy('id_movimiento', 'desc')->limit(20)->get(),
        ];
    }

    private function getDatosProduccion()
    {
        return [
            'productosMasCreados' => DetalleProduccion::where('tipo_movimiento', 'ingreso')
                ->join('items', 'detalle_produccion.id_item', '=', 'items.id_item')
                ->select('items.nombre', DB::raw('SUM(detalle_produccion.cantidad) as total_producido'))
                ->groupBy('items.id_item', 'items.nombre')
                ->orderBy('total_producido', 'desc')->limit(10)->get(),
            'insumosMasUsados' => DetalleProduccion::where('tipo_movimiento', 'egreso')
                ->join('items', 'detalle_produccion.id_item', '=', 'items.id_item')
                ->select('items.nombre', DB::raw('SUM(detalle_produccion.cantidad) as total_consumido'))
                ->groupBy('items.id_item', 'items.nombre')
                ->orderBy('total_consumido', 'desc')->limit(10)->get(),
        ];
    }

    private function getDatosComercial($request)
    {
        $inicio = $request->fecha_inicio ?? now()->subDays(30)->toDateString();
        $fin = $request->fecha_fin ?? now()->toDateString();

        return [
            'inicio' => $inicio,
            'fin' => $fin,
            'ventas' => NotaVenta::whereBetween('fecha_venta', [$inicio, $fin])
                ->where('estado', 'completado')
                ->with('cliente', 'empleado')
                ->get(),
            'compras' => NotaCompra::whereBetween('fecha_compra', [$inicio, $fin])
                ->where('estado', 'completado')
                ->with('proveedor', 'empleado')
                ->get(),
            'totalVentas' => NotaVenta::whereBetween('fecha_venta', [$inicio, $fin])
                ->where('estado', 'completado')->sum('monto_total'),
            'totalCompras' => NotaCompra::whereBetween('fecha_compra', [$inicio, $fin])
                ->where('estado', 'completado')->sum('monto_total'),
        ];
    }
}