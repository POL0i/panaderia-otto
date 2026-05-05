{{-- resources/views/produccion/producciones/index.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Órdenes de Producción - Panadería Otto')
@section('page-title', 'Listado de Producciones')
@section('page-description', 'Gestión de órdenes de producción')

@section('content')
<div class="container-fluid">
    
    {{-- Filtros rápidos por estado --}}
    <div class="row mb-3">
        <div class="col-12">
            <div class="btn-group">
                <a href="{{ route('producciones.index') }}" class="btn btn-outline-secondary {{ !request('estado') ? 'active' : '' }}">
                    Todas
                </a>
                <a href="{{ route('producciones.index', ['estado' => 'pendiente']) }}" class="btn btn-outline-warning {{ request('estado') == 'pendiente' ? 'active' : '' }}">
                    Pendientes
                    <span class="badge badge-warning ml-1">{{ \App\Models\Produccion::where('estado', 'pendiente')->count() }}</span>
                </a>
                <a href="{{ route('producciones.index', ['estado' => 'aprobado']) }}" class="btn btn-outline-success {{ request('estado') == 'aprobado' ? 'active' : '' }}">
                    Aprobadas
                </a>
                <a href="{{ route('producciones.index', ['estado' => 'rechazado']) }}" class="btn btn-outline-danger {{ request('estado') == 'rechazado' ? 'active' : '' }}">
                    Rechazadas
                </a>
                <a href="{{ route('producciones.index', ['estado' => 'cancelado']) }}" class="btn btn-outline-dark {{ request('estado') == 'cancelado' ? 'active' : '' }}">
                    Canceladas
                </a>
            </div>
            <a href="{{ route('produccion.index') }}" class="btn btn-primary float-right">
                <i class="fas fa-plus"></i> Nueva Producción
            </a>
        </div>
    </div>

    {{-- Tabla de producciones --}}
    <div class="card">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-industry"></i> 
                {{ request('estado') ? ucfirst(request('estado')) . 's' : 'Todas las' }} Producciones
            </h5>
        </div>
        <div class="card-body">
            @if($producciones->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Fecha Prod.</th>
                                <th>Receta</th>
                                <th>Cantidad</th>
                                <th>Solicitante</th>
                                <th>Estado</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($producciones as $produccion)
                            <tr>
                                <td><strong>#{{ $produccion->id_produccion }}</strong></td>
                                <td>{{ \Carbon\Carbon::parse($produccion->fecha_produccion)->format('d/m/Y') }}</td>
                                <td>
                                    @php
                                        // Obtener receta a través de los detalles
                                        $receta = $produccion->detalles()
                                            ->whereNotNull('id_detalle_receta')
                                            ->first()?->detalleReceta?->receta;
                                    @endphp
                                    {{ $receta->nombre ?? 'N/A' }}
                                </td>
                                <td>{{ $produccion->cantidad_producida }}</td>
                                <td>{{ $produccion->empleadoSolicita->nombre ?? 'N/A' }}</td>
                                <td>
                                    @switch($produccion->estado)
                                        @case('pendiente')
                                            <span class="badge badge-warning">Pendiente</span>
                                            @break
                                        @case('aprobado')
                                            <span class="badge badge-success">Aprobado</span>
                                            @break
                                        @case('rechazado')
                                            <span class="badge badge-danger">Rechazado</span>
                                            @break
                                        @case('cancelado')
                                            <span class="badge badge-secondary">Cancelado</span>
                                            @break
                                    @endswitch
                                </td>
                                <td>
                                    <a href="{{ route('producciones.show', $produccion) }}" class="btn btn-sm btn-info" title="Ver detalle">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{ $producciones->links() }}
            @else
                <div class="text-center py-5">
                    <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay producciones registradas</h5>
                    <p class="text-muted">
                        @if(request('estado'))
                            No hay producciones en estado "{{ request('estado') }}".
                        @else
                            Comienza creando una nueva orden de producción.
                        @endif
                    </p>
                    <a href="{{ route('produccion.index') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Nueva Producción
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

{{-- Modal para rechazar (solicitar motivo) --}}
<div class="modal fade" id="modalMotivoRechazo" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title"><i class="fas fa-times-circle"></i> Rechazar Producción</h5>
                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
            </div>
            <form id="formRechazo" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Motivo del rechazo <span class="text-danger">*</span></label>
                        <textarea name="motivo" class="form-control" rows="3" required 
                                  placeholder="Explique por qué se rechaza esta producción..."></textarea>
                    </div>
                    <input type="hidden" name="produccion_id" id="produccionIdRechazo">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-danger">Confirmar Rechazo</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
// Función para cambiar estado (aprobar/cancelar) o abrir modal de rechazo
function cambiarEstado(id, accion) {
    if (accion === 'rechazar') {
        // Abrir modal para pedir motivo
        $('#produccionIdRechazo').val(id);
        $('#formRechazo').attr('action', '/producciones/' + id + '/rechazar');
        $('#modalMotivoRechazo').modal('show');
        return;
    }
    
    // Confirmación para aprobar o cancelar
    let mensaje = accion === 'aprobar' 
        ? '¿Está seguro de APROBAR esta producción? Se descontarán los insumos del inventario y se ingresará el producto final.'
        : '¿Está seguro de CANCELAR esta producción?';
    
    if (!confirm(mensaje)) return;
    
    // Crear formulario dinámico y enviar
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = '/producciones/' + id + '/' + accion;
    
    let csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
}

// Manejar envío del formulario de rechazo
$('#formRechazo').on('submit', function(e) {
    e.preventDefault();
    
    let id = $('#produccionIdRechazo').val();
    let formData = new FormData(this);
    
    fetch(this.action, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            $('#modalMotivoRechazo').modal('hide');
            toastr.success('Producción rechazada correctamente');
            setTimeout(() => location.reload(), 1000);
        } else {
            toastr.error(data.message || 'Error al rechazar');
        }
    })
    .catch(error => {
        toastr.error('Error al procesar la solicitud');
    });
});
</script>
@endpush