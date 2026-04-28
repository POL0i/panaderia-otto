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
                    <a href="{{ route('producciones.index') }}" class="btn btn-warning quick-action-btn">
                        <i class="fas fa-list-alt"></i> Ver Órdenes de Producción
                    </a>
                    <a href="{{ route('recetas.index') }}" class="btn btn-secondary quick-action-btn">
                        <i class="fas fa-list"></i> Ver Todas las Recetas
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Sección: Nueva Producción --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header" style="background: #FFF5E6; border-bottom: 2px solid #D2B48C;">
                    <h5 class="mb-0" style="color: #5D3A1A;">
                        <i class="fas fa-industry"></i> Nueva Orden de Producción
                    </h5>
                </div>
                <div class="card-body">
                    <form id="formNuevaProduccion" action="{{ route('producciones.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Receta a producir <span class="text-danger">*</span></label>
                                    <select name="id_receta" id="produccion_receta" class="form-control" required>
                                        <option value="">Seleccione receta...</option>
                                        @foreach(\App\Models\Receta::with('producto')->get() as $receta)
                                            <option value="{{ $receta->id_receta }}" 
                                                    data-producto-id="{{ $receta->producto->id_producto ?? '' }}"
                                                    data-producto-item="{{ $receta->producto->item->id_item ?? '' }}">
                                                {{ $receta->nombre }}
                                                @if($receta->producto)
                                                    (Producto: {{ $receta->producto->nombre }})
                                                @else
                                                    (Sin producto asignado)
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Cantidad a producir <span class="text-danger">*</span></label>
                                    <input type="number" name="cantidad_producida" id="produccion_cantidad" 
                                        class="form-control" step="0.1" min="0.1" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Almacén destino (producto final) <span class="text-danger">*</span></label>
                                    <select name="almacen_destino" id="produccion_almacen_destino" class="form-control" required>
                                        <option value="">Seleccione almacén...</option>
                                        @foreach(\App\Models\Almacen::all() as $almacen)
                                            <option value="{{ $almacen->id_almacen }}">{{ $almacen->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label>Notificar a empleado (opcional)</label>
                                    <select name="notificar_empleado" class="form-control">
                                        <option value="">No notificar</option>
                                        @foreach(\App\Models\Empleado::all() as $emp)
                                            <option value="{{ $emp->id_empleado }}">{{ $emp->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Observaciones</label>
                                    <textarea name="observaciones" class="form-control" rows="2"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- Previsualización de insumos requeridos --}}
                        <div class="row mt-3">
                            <div class="col-12">
                                <h6>Insumos requeridos:</h6>
                                <div id="preview-insumos" class="table-responsive">
                                    <p class="text-muted">Seleccione receta y cantidad para calcular.</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit" class="btn btn-primary" id="btnCrearProduccion">
                                <i class="fas fa-play"></i> Solicitar Producción
                            </button>
                        </div>
                    </form>
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

{{-- Modal Crear Receta CON Insumos y Producto --}}
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Nombre de la Receta <span class="text-danger">*</span></label>
                                <input type="text" name="nombre" id="recetaNombre" class="form-control" 
                                       placeholder="Ej: Pan Francés, Tarta de Manzana..." required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Producto Final <span class="text-danger">*</span></label>
                                <select name="id_producto" id="recetaProducto" class="form-control" required>
                                    <option value="">Seleccione producto...</option>
                                    @foreach(\App\Models\Producto::with('item')->get() as $producto)
                                        <option value="{{ $producto->id_producto }}">
                                            {{ $producto->nombre }} ({{ $producto->item->unidad_medida ?? 'unidad' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
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
                    
                    {{-- Selector de insumos (se mantiene igual) --}}
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
                        Selecciona los insumos, especifica la cantidad y el producto final.
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
        var id_producto = $('#recetaProducto').val();
        
        if (!nombre) {
            toastr.error('El nombre de la receta es requerido');
            return;
        }
        if (!id_producto) {
            toastr.error('Debe seleccionar un producto final para la receta');
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
        
        // Crear receta primero (ahora con id_producto)
        $.ajax({
            url: '{{ route("produccion.recetas.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                nombre: nombre,
                descripcion: descripcion,
                id_producto: id_producto
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

    $('#produccion_receta, #produccion_cantidad').on('change keyup', function() {
        var recetaId = $('#produccion_receta').val();
        var cantidad = parseFloat($('#produccion_cantidad').val());

        if (recetaId && cantidad && cantidad > 0) {
            $.ajax({
                url: '{{ route("producciones.calcular-insumos") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id_receta: recetaId,
                    cantidad: cantidad
                },
                success: function(response) {
                    var html = '<table class="table table-sm table-bordered">';
                    html += '<thead><tr><th>Insumo</th><th>Cantidad teórica (base)</th><th>Cantidad requerida</th><th>Unidad</th></tr></thead><tbody>';
                    response.insumos.forEach(function(ins) {
                        html += '<tr>';
                        html += '<td>' + ins.insumo + '</td>';
                        html += '<td>' + ins.cantidad_teorica + '</td>';
                        html += '<td><strong>' + ins.cantidad_requerida.toFixed(3) + '</strong></td>';
                        html += '<td>' + ins.unidad + '</td>';
                        html += '</tr>';
                    });
                    html += '</tbody></table>';
                    $('#preview-insumos').html(html);
                },
                error: function() {
                    $('#preview-insumos').html('<p class="text-danger">Error al calcular insumos.</p>');
                }
            });
        } else {
            $('#preview-insumos').html('<p class="text-muted">Seleccione receta y cantidad para calcular.</p>');
        }
    });
    
});
</script>
@endpush