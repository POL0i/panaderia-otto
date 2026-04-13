@extends('layouts.adminlte')

@section('title', 'Detalles de Venta')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-shopping-cart icon-panaderia"></i> Detalles de Venta</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('detalles-venta.create') }}" class="btn btn-save btn-sm">
                <i class="fas fa-plus"></i> Crear Detalle
            </a>
        </div>
    </div>

    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ $message }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Detalles de Venta</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nota #</th>
                        <th>Producto</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detallesVenta as $detalle)
                        <tr>
                            <td><span class="badge badge-info">#{{ $detalle->id_nota_venta }}</span></td>
                            <td><i class="fas fa-bread-slice text-warning"></i> {{ $detalle->producto->nombre ?? 'N/A' }}</td>
                            <td class="text-right">{{ $detalle->cantidad }}</td>
                            <td class="text-right">${{ number_format($detalle->precio, 2) }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('detalles-venta.edit', [$detalle->id_nota_venta, $detalle->id_producto]) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('detalles-venta.destroy', [$detalle->id_nota_venta, $detalle->id_producto]) }}" method="POST" style="display:inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" title="Eliminar" onclick="return confirm('¿Está seguro?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center text-muted py-4">
                                <i class="fas fa-inbox"></i> No hay detalles de venta registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($detallesVenta->count() > 0)
            <div class="card-footer d-flex justify-content-center">
                {{ $detallesVenta->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
