{{-- resources/views/produccion/index.blade.php --}}
@extends('layouts.adminlte')

@section('title', 'Módulo de Producción - Panadería Otto')
@section('page-title', 'Panel de Producción')
@section('page-description', 'Gestión de recetas, insumos y categorías')

@push('styles')
<style>
    .module-card {
        transition: all 0.3s ease;
        cursor: pointer;
        border-radius: 15px;
        overflow: hidden;
        height: 100%;
    }
    .module-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.15);
    }
    .module-icon {
        font-size: 48px;
        margin-bottom: 15px;
        color: #8B4513;
    }
    .module-title {
        font-size: 20px;
        font-weight: 600;
        margin-bottom: 10px;
        color: #5D3A1A;
    }
    .stats-card {
        background: linear-gradient(135deg, #8B4513 0%, #5D3A1A 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
        margin-bottom: 20px;
    }
    .stats-number {
        font-size: 36px;
        font-weight: bold;
    }
    .quick-action-btn {
        margin: 5px;
        padding: 12px 20px;
        font-size: 16px;
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    
    {{-- Estadísticas rápidas --}}
    <div class="row">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">{{ $totalRecetas ?? 0 }}</div>
                <div class="stats-label">Recetas Totales</div>
                <i class="fas fa-utensils float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #A0522D 0%, #8B4513 100%);">
                <div class="stats-number">{{ $totalProducciones ?? 0 }}</div>
                <div class="stats-label">Producciones</div>
                <i class="fas fa-chart-bar float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #CD853F 0%, #A0522D 100%);">
                <div class="stats-number">{{ $totalCategorias ?? 0 }}</div>
                <div class="stats-label">Categorías</div>
                <i class="fas fa-folder float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card" style="background: linear-gradient(135deg, #DEB887 0%, #CD853F 100%);">
                <div class="stats-number">{{ $totalInsumos ?? 0 }}</div>
                <div class="stats-label">Insumos</div>
                <i class="fas fa-box float-right" style="font-size: 40px; opacity: 0.3;"></i>
            </div>
        </div>
    </div>

    {{-- Acciones rápidas --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-bolt"></i> Acciones Rápidas - Crear Nuevo
                    </h5>
                </div>
                <div class="card-body text-center">
                    <button class="btn btn-success quick-action-btn" data-toggle="modal" data-target="#createCategoriaModal">
                        <i class="fas fa-folder-plus"></i> Nueva Categoría
                    </button>
                    <button class="btn btn-info quick-action-btn" data-toggle="modal" data-target="#createInsumoModal">
                        <i class="fas fa-box-open"></i> Nuevo Insumo
                    </button>
                    <button class="btn btn-primary quick-action-btn" data-toggle="modal" data-target="#createRecetaModal">
                        <i class="fas fa-book-medical"></i> Nueva Receta
                    </button>
                    <a href="{{ route('recetas.index') }}" class="btn btn-secondary quick-action-btn">
                        <i class="fas fa-list"></i> Ver Todas las Recetas
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Módulos disponibles --}}
    <div class="row mt-4">
        <div class="col-12">
            <h4 class="mb-3" style="color: #5D3A1A;">
                <i class="fas fa-cubes"></i> Módulos de Producción
            </h4>
        </div>
    </div>

    <div class="row">
        {{-- Recetas --}}
        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="window.location='{{ route('recetas.index') }}'">
                <div class="card-body text-center p-4">
                    <div class="module-icon">
                        <i class="fas fa-book-open"></i>
                    </div>
                    <div class="module-title">Recetas</div>
                    <p class="text-muted">
                        Gestiona las recetas y fórmulas de tus productos.
                    </p>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-list"></i> {{ $totalRecetas ?? 0 }} recetas registradas
                    </small>
                </div>
            </div>
        </div>

        {{-- Categorías de Insumos --}}
        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="$('#createCategoriaModal').modal('show')">
                <div class="card-body text-center p-4">
                    <div class="module-icon">
                        <i class="fas fa-folder-tree"></i>
                    </div>
                    <div class="module-title">Categorías</div>
                    <p class="text-muted">
                        Organiza tus insumos por categorías.
                    </p>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-folder"></i> {{ $totalCategorias ?? 0 }} categorías
                    </small>
                </div>
            </div>
        </div>

        {{-- Insumos --}}
        <div class="col-md-4 mb-4">
            <div class="card module-card" onclick="$('#createInsumoModal').modal('show')">
                <div class="card-body text-center p-4">
                    <div class="module-icon">
                        <i class="fas fa-boxes"></i>
                    </div>
                    <div class="module-title">Insumos</div>
                    <p class="text-muted">
                        Administra los ingredientes y materias primas.
                    </p>
                    <hr>
                    <small class="text-muted">
                        <i class="fas fa-box"></i> {{ $totalInsumos ?? 0 }} insumos
                    </small>
                </div>
            </div>
        </div>
    </div>

    {{-- Últimas recetas creadas --}}
    @if(isset($ultimasRecetas) && count($ultimasRecetas) > 0)
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">
                        <i class="fas fa-clock"></i> Últimas Recetas Creadas
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Nombre</th>
                                    <th>Descripción</th>
                                    <th>Insumos</th>
                                    <th>Creada</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($ultimasRecetas as $receta)
                                <tr>
                                    <td><strong>{{ $receta->nombre }}</strong></td>
                                    <td>{{ Str::limit($receta->descripcion, 40) ?: '-' }}</td>
                                    <td>
                                        <span class="badge badge-info">{{ $receta->detalles_count ?? 0 }} insumos</span>
                                    </td>
                                    <td>{{ $receta->created_at->diffForHumans() }}</td>
                                    <td>
                                        <a href="{{ route('recetas.show', $receta) }}" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('produccion.recetas.detalles', $receta) }}" class="btn btn-sm btn-success">
                                            <i class="fas fa-plus-circle"></i> Insumos
                                        </a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>

{{-- ===================================================== --}}
{{-- MODALES --}}
{{-- ===================================================== --}}

{{-- Modal Crear Categoría --}}
<div class="modal fade" id="createCategoriaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h5 class="modal-title">
                    <i class="fas fa-folder-plus"></i> Nueva Categoría de Insumo
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateCategoria" action="{{ route('produccion.categorias.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" class="form-control" 
                               placeholder="Ej: Harinas, Lácteos, Endulzantes..." required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" class="form-control" rows="2" 
                                  placeholder="Descripción opcional de la categoría"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-success">Crear Categoría</button>
                </div>
            </form>
        </div>
    </div>
</div>

    {{-- Modal Crear Insumo --}}
    <div class="modal fade" id="createInsumoModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h5 class="modal-title">
                        <i class="fas fa-box-open"></i> Nuevo Insumo
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <form id="formCreateInsumo">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Nombre del Insumo <span class="text-danger">*</span></label>
                            <input type="text" name="nombre" id="insumoNombre" class="form-control" 
                                placeholder="Ej: Harina de trigo, Azúcar, Huevos..." required>
                        </div>
                        
                        <div class="form-group">
                            <label>Categoría <span class="text-danger">*</span></label>
                            <select name="id_cat_insumo" id="insumoCategoria" class="form-control" required>
                                <option value="">Seleccionar categoría...</option>
                                @foreach($categorias ?? [] as $categoria)
                                    <option value="{{ $categoria->id_cat_insumo }}">{{ $categoria->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Unidad de Medida <span class="text-danger">*</span></label>
                            <select name="unidad_medida" id="insumoUnidad" class="form-control" required>
                                <option value="kg">Kilogramos (kg)</option>
                                <option value="g">Gramos (g)</option>
                                <option value="lb">Libras (lb)</option>
                                <option value="oz">Onzas (oz)</option>
                                <option value="L">Litros (L)</option>
                                <option value="mL">Mililitros (mL)</option>
                                <option value="unidad">Unidad</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Precio de Compra</label>
                            <input type="number" name="precio_compra" id="insumoPrecio" class="form-control" 
                                step="0.01" min="0" placeholder="0.00">
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> 
                            Se creará automáticamente un registro en Items como "insumo".
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-info">
                            <i class="fas fa-save"></i> Crear Insumo
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

{{-- Modal Crear Receta (Básica) --}}
{{-- Modal Crear Receta CON Insumos --}}
<div class="modal fade" id="createRecetaModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header bg-primary">
                <h5 class="modal-title">
                    <i class="fas fa-book-medical"></i> Nueva Receta
                </h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCreateRecetaCompleta">
                @csrf
                <div class="modal-body">
                    {{-- Datos básicos de la receta --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Nombre de la Receta <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="recetaNombre" class="form-control" 
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Descripción</label>
                                <textarea name="descripcion" id="recetaDescripcion" class="form-control" rows="2" 
                                          placeholder="Describe brevemente la receta..."></textarea>
                            </div>
                        </div>
                    </div>

                    <hr>
                    <h6><i class="fas fa-boxes"></i> Agregar Insumos a la Receta</h6>
                    
                    {{-- Selector de insumos --}}
                    <div id="insumosContainer" style="max-height: 300px; overflow-y: auto; border: 1px solid #ddd; border-radius: 8px; padding: 10px; margin-bottom: 15px;">
                        @foreach($categorias ?? [] as $categoria)
                            @if($categoria->insumos->count() > 0)
                                <div class="categoria-section mb-3">
                                    <div class="categoria-header bg-light p-2 mb-2" style="border-left: 4px solid #8B4513;">
                                        <strong><i class="fas fa-folder"></i> {{ $categoria->nombre }}</strong>
                                        <span class="badge badge-secondary ml-2">{{ $categoria->insumos->count() }}</span>
                                    </div>
                                    <div class="row">
                                        @foreach($categoria->insumos as $insumo)
                                            <div class="col-md-6 mb-2">
                                                <div class="insumo-item border rounded p-2">
                                                    <div class="custom-control custom-checkbox mb-2">
                                                        <input type="checkbox" 
                                                               class="custom-control-input insumo-checkbox" 
                                                               id="modal_insumo_{{ $insumo->id_insumo }}"
                                                               value="{{ $insumo->id_insumo }}">
                                                        <label class="custom-control-label" for="modal_insumo_{{ $insumo->id_insumo }}">
                                                            <strong>{{ $insumo->nombre }}</strong>
                                                        </label>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-7">
                                                            <input type="number" 
                                                                   name="cantidad_{{ $insumo->id_insumo }}" 
                                                                   class="form-control form-control-sm cantidad-insumo"
                                                                   placeholder="Cantidad"
                                                                   step="0.001" min="0.001"
                                                                   disabled>
                                                        </div>
                                                        <div class="col-5">
                                                            <select name="unidad_{{ $insumo->id_insumo }}" 
                                                                    class="form-control form-control-sm unidad-insumo"
                                                                    disabled>
                                                                <option value="kg">kg</option>
                                                                <option value="g">g</option>
                                                                <option value="lb">lb</option>
                                                                <option value="oz">oz</option>
                                                                <option value="L">L</option>
                                                                <option value="mL">mL</option>
                                                                <option value="unidad">unidad</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>

                    <div class="text-right">
                        <span id="insumosSeleccionadosCount" class="mr-2">0 insumos seleccionados</span>
                    </div>

                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> 
                        Selecciona los insumos y especifica la cantidad para cada uno.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary" id="btnCrearRecetaCompleta">
                        <i class="fas fa-save"></i> Crear Receta con Insumos
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Habilitar/deshabilitar campos de cantidad al marcar checkbox
    $(document).on('change', '.insumo-checkbox', function() {
        var container = $(this).closest('.insumo-item');
        var isChecked = $(this).prop('checked');
        
        container.find('.cantidad-insumo, .unidad-insumo').prop('disabled', !isChecked);
        
        if (isChecked) {
            container.find('.cantidad-insumo').prop('required', true).val('1');
        } else {
            container.find('.cantidad-insumo').prop('required', false).val('');
        }
        
        actualizarContadorInsumos();
    });
    
    // Actualizar contador de insumos seleccionados
    function actualizarContadorInsumos() {
        var count = $('.insumo-checkbox:checked').length;
        $('#insumosSeleccionadosCount').text(count + ' insumos seleccionados');
    }
    
    // Crear Receta CON Insumos
    $('#formCreateRecetaCompleta').on('submit', function(e) {
        e.preventDefault();
        
        var nombre = $('#recetaNombre').val();
        var descripcion = $('#recetaDescripcion').val();
        
        if (!nombre) {
            toastr.error('El nombre de la receta es requerido');
            return;
        }
        
        // Recopilar insumos seleccionados
        var insumos = [];
        var tieneInsumos = false;
        
        $('.insumo-checkbox:checked').each(function() {
            var id = $(this).val();
            var container = $(this).closest('.insumo-item');
            var cantidad = container.find('.cantidad-insumo').val();
            var unidad = container.find('.unidad-insumo').val();
            
            if (cantidad && cantidad > 0) {
                insumos.push({
                    id_insumo: id,
                    cantidad: cantidad,
                    unidad: unidad
                });
                tieneInsumos = true;
            }
        });
        
        // Crear receta primero
        $.ajax({
            url: '{{ route("produccion.recetas.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre,
                descripcion: descripcion
            },
            success: function(response) {
                if (response.success && response.receta) {
                    var recetaId = response.receta.id_receta;
                    
                    // Si hay insumos, agregarlos
                    if (tieneInsumos) {
                        $.ajax({
                            url: '/produccion/recetas/' + recetaId + '/detalles',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                insumos: insumos
                            },
                            success: function(res) {
                                $('#createRecetaModal').modal('hide');
                                toastr.success('Receta creada con ' + insumos.length + ' insumos');
                                $('#formCreateRecetaCompleta')[0].reset();
                                $('.insumo-checkbox').prop('checked', false).trigger('change');
                                
                                // Redirigir a los detalles de la receta
                                setTimeout(() => {
                                    window.location.href = '/produccion/recetas/' + recetaId + '/detalles';
                                }, 1000);
                            },
                            error: function() {
                                toastr.warning('Receta creada pero hubo error al agregar insumos');
                                setTimeout(() => location.reload(), 1500);
                            }
                        });
                    } else {
                        $('#createRecetaModal').modal('hide');
                        toastr.success('Receta creada correctamente');
                        $('#formCreateRecetaCompleta')[0].reset();
                        setTimeout(() => {
                            window.location.href = '/produccion/recetas/' + recetaId + '/detalles';
                        }, 1000);
                    }
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la receta';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                toastr.error(message);
            }
        });
    });
    
    // Crear Categoría
    $('#formCreateCategoria').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(response) {
                if (response.success) {
                    $('#createCategoriaModal').modal('hide');
                    toastr.success(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear la categoría';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                }
                toastr.error(message);
            }
        });
    });
    
    // Crear Insumo (con Item automático)
    $('#formCreateInsumo').on('submit', function(e) {
        e.preventDefault();
        var form = $(this);
        
        var data = {
            _token: '{{ csrf_token() }}',
            nombre: $('#insumoNombre').val(),
            id_cat_insumo: $('#insumoCategoria').val(),
            unidad_medida: $('#insumoUnidad').val(),
            precio_compra: $('#insumoPrecio').val() || null
        };
        
        $.ajax({
            url: '/produccion/insumos',
            method: 'POST',
            data: data,
            success: function(response) {
                if (response.success) {
                    $('#createInsumoModal').modal('hide');
                    alert(response.message);
                    form[0].reset();
                    setTimeout(() => location.reload(), 1000);
                }
            },
            error: function(xhr) {
                var message = 'Error al crear el insumo';
                if (xhr.responseJSON?.errors) {
                    message = Object.values(xhr.responseJSON.errors).flat().join('\n');
                } else if (xhr.responseJSON?.message) {
                    message = xhr.responseJSON.message;
                }
                alert(message);
            }
        });
    });
    
});
</script>
@endpush