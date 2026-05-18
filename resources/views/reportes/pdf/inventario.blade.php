<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Inventario</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { color: #8B4513; text-align: center; }
        h2 { color: #5D3A1A; border-bottom: 1px solid #D2B48C; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        th { background: #8B4513; color: white; padding: 6px; font-size: 11px; }
        td { border: 1px solid #ddd; padding: 5px; font-size: 11px; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .badge-danger { color: #dc3545; font-weight: bold; }
        .badge-warning { color: #ffc107; font-weight: bold; }
    </style>
</head>
<body>
    <h1>PANADERÍA OTTO</h1>
    <p style="text-align:center;">Reporte de Inventario - {{ date('d/m/Y H:i') }}</p>

    <h2>Stock Bajo (<30%)</h2>
    <table>
        <thead><tr><th>Item</th><th>Almacén</th><th class="text-center">Disponible</th><th class="text-center">Inicial</th><th class="text-center">%</th></tr></thead>
        <tbody>
            @foreach($lotesBajos as $lote)
            <tr>
                <td>{{ $lote->item_nombre }}</td>
                <td>{{ $lote->almacen_nombre }}</td>
                <td class="text-center badge-danger">{{ $lote->cantidad_disponible }}</td>
                <td class="text-center">{{ $lote->cantidad_inicial }}</td>
                <td class="text-center">{{ round(($lote->cantidad_disponible/$lote->cantidad_inicial)*100) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Próximos a Vencer (7 días)</h2>
    <table>
        <thead><tr><th>Item</th><th>Almacén</th><th class="text-center">Vencimiento</th><th class="text-center">Días</th></tr></thead>
        <tbody>
            @foreach($lotesPorVencer as $lote)
            <tr>
                <td>{{ $lote->item_nombre }}</td>
                <td>{{ $lote->almacen_nombre }}</td>
                <td class="text-center badge-warning">{{ $lote->fecha_vencimiento->format('d/m/Y') }}</td>
                <td class="text-center">{{ now()->diffInDays($lote->fecha_vencimiento) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Últimos Movimientos</h2>
    <table>
        <thead><tr><th>#</th><th>Fecha</th><th>Tipo</th><th>Item</th><th class="text-center">Cant.</th></tr></thead>
        <tbody>
            @foreach($movimientos as $mov)
            <tr>
                <td>{{ $mov->id_movimiento }}</td>
                <td>{{ date('d/m/Y H:i', strtotime($mov->fecha_movimiento)) }}</td>
                <td>{{ $mov->tipo_movimiento == 'ingreso' ? 'Ingreso' : 'Egreso' }}</td>
                <td>{{ \App\Models\Item::find($mov->id_item)->nombre ?? 'Item' }}</td>
                <td class="text-center">{{ $mov->cantidad }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>