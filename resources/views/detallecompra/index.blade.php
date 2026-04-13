@extends('layouts.adminlte')

@section('title', 'Detalles de Compra')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-barcode icon-panaderia"></i> Detalles de Compra</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('detalles-compra.create') }}" class="btn btn-save btn-sm">
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
            <h5 class="mb-0"><i class="fas fa-list"></i> Listado de Detalles de Compra</h5>
        </div>
        <div class="table-responsive">
            <table class="table table-hover table-sm mb-0">
                <thead class="bg-light">
                    <tr>
                        <th>Nota #</th>
                        <th>Insumo</th>
                        <th class="text-right">Cantidad</th>
                        <th class="text-right">Precio Unit.</th>
                        <th class="text-right">Subtotal</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($detallesCompra as $detalle)
                        <tr>
                            <td><span class="badge badge-info">#{{ $detalle->id_nota_compra }}</span></td>
                            <td><i class="fas fa-box text-primary"></i> {{ $detalle->insumo->nombre ?? 'N/A' }}</td>
                            <td class="text-right">{{ $detalle->cantidad }}</td>
                            <td class="text-right">${{ number_format($detalle->precio, 2) }}</td>
                            <td class="text-right font-weight-bold">${{ number_format($detalle->cantidad * $detalle->precio, 2) }}</td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ route('detalles-compra.edit', [$detalle->id_nota_compra, $detalle->id_insumo]) }}" class="btn btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('detalles-compra.destroy', [$detalle->id_nota_compra, $detalle->id_insumo]) }}" method="POST" style="display:inline;">
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
                                <i class="fas fa-inbox"></i> No hay detalles de compra registrados
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($detallesCompra->count() > 0)
            <div class="card-footer d-flex justify-content-center">
                {{ $detallesCompra->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
