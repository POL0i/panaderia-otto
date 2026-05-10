@extends('layouts.adminlte')

@section('title', 'Editar Item')
@section('page-title', 'Editar Item')

@push('styles')
<style>
    .img-thumbnail {
        max-height: 120px;
        object-fit: cover;
    }
    .btn-edit-categoria {
        padding: 2px 8px;
        font-size: 12px;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-md-8">
        <form action="{{ route('items.update', $item->id_item) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Sección 1: Datos generales --}}
            <div class="card">
                <div class="card-header bg-gradient-primary">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle mr-2"></i> Datos Generales
                    </h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="nombre">Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="nombre" class="form-control @error('nombre') is-invalid @enderror"
                               value="{{ old('nombre', $item->nombre) }}" required>
                        @error('nombre')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label>Tipo de Item</label>
                        <input type="text" class="form-control bg-light" 
                               value="{{ $item->tipo_item == 'producto' ? '📦 Producto' : '🧪 Insumo' }}" readonly>
                        <input type="hidden" name="tipo_item" value="{{ $item->tipo_item }}">
                    </div>

                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                        <select name="unidad_medida" id="unidad_medida" class="form-control @error('unidad_medida') is-invalid @enderror" required>
                            @foreach(['kg','g','lb','oz','L','mL','unidad','docena','paquete','bandeja'] as $um)
                                <option value="{{ $um }}" {{ old('unidad_medida', $item->unidad_medida) == $um ? 'selected' : '' }}>{{ $um }}</option>
                            @endforeach
                        </select>
                        @error('unidad_medida')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            {{-- Sección 2: Producto --}}
            @if($item->tipo_item == 'producto' && $item->producto)
            <div class="card collapsed-card" id="card-producto">
                <div class="card-header bg-success" data-card-widget="collapse">
                    <h3 class="card-title text-white">
                        <i class="fas fa-box mr-2"></i> Información del Producto
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Categoría con botón de editar --}}
                    <div class="form-group">
                        <label for="id_cat_producto">Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_producto" id="id_cat_producto" class="form-control @error('id_cat_producto') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                @foreach($categoriasProductos as $cat)
                                    <option value="{{ $cat->id_cat_producto }}"
                                        {{ old('id_cat_producto', $item->producto->id_cat_producto) == $cat->id_cat_producto ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-success" data-toggle="modal" data-target="#createCategoriaProductoModal" title="Nueva categoría">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-edit-categoria-producto" 
                                        data-id="{{ $item->producto->id_cat_producto }}"
                                        data-nombre="{{ $item->producto->categoria->nombre ?? '' }}"
                                        data-descripcion="{{ $item->producto->categoria->descripcion ?? '' }}"
                                        title="Editar categoría actual">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        @error('id_cat_producto')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Precio --}}
                    <div class="form-group">
                        <label for="precio">Precio de Venta <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" name="precio" id="precio" class="form-control @error('precio') is-invalid @enderror"
                                   value="{{ old('precio', $item->producto->precio) }}" required>
                        </div>
                        @error('precio')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Imagen actual --}}
                    @if($item->producto->imagen)
                    <div class="form-group">
                        <label>Imagen Actual</label>
                        <div class="mb-2">
                            @php
                                $imagen = $item->producto->imagen;
                                $esUrl = Str::startsWith($imagen, ['http://', 'https://']);
                                $src = $esUrl ? $imagen : asset('storage/' . $imagen);
                            @endphp
                            <img src="{{ $src }}" class="img-thumbnail" style="max-width: 150px;"
                                 onerror="this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%2250%22 height=%2250%22><rect fill=%22%23eee%22 width=%2250%22 height=%2250%22/><text x=%2225%22 y=%2230%22 text-anchor=%22middle%22 fill=%22%23999%22 font-size=%2210%22>No img</text></svg>'">
                        </div>
                    </div>
                    @endif

                    {{-- Subir nueva imagen --}}
                    <div class="form-group">
                        <label for="imagen">Subir Nueva Imagen</label>
                        <div class="custom-file">
                            <input type="file" name="imagen" id="imagen" class="custom-file-input" accept="image/*">
                            <label class="custom-file-label" for="imagen">Seleccionar archivo</label>
                        </div>
                        <small class="form-text text-muted">jpg, png, gif. Máx 2MB</small>
                    </div>

                    {{-- URL de imagen --}}
                    <div class="form-group">
                        <label for="imagen_url">O usar URL</label>
                        <input type="url" name="imagen_url" id="imagen_url" class="form-control"
                               placeholder="https://ejemplo.com/imagen.jpg" value="{{ old('imagen_url') }}">
                    </div>
                </div>
            </div>
            @endif

            {{-- Sección 3: Insumo --}}
            @if($item->tipo_item == 'insumo' && $item->insumo)
            <div class="card collapsed-card" id="card-insumo">
                <div class="card-header bg-info" data-card-widget="collapse">
                    <h3 class="card-title text-white">
                        <i class="fas fa-flask mr-2"></i> Información del Insumo
                    </h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-tool" data-card-widget="collapse">
                            <i class="fas fa-plus"></i>
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    {{-- Categoría con botón de editar --}}
                    <div class="form-group">
                        <label for="id_cat_insumo">Categoría <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <select name="id_cat_insumo" id="id_cat_insumo" class="form-control @error('id_cat_insumo') is-invalid @enderror">
                                <option value="">Seleccione...</option>
                                @foreach($categoriasInsumos as $cat)
                                    <option value="{{ $cat->id_cat_insumo }}"
                                        {{ old('id_cat_insumo', $item->insumo->id_cat_insumo) == $cat->id_cat_insumo ? 'selected' : '' }}>
                                        {{ $cat->nombre }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="input-group-append">
                                <button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#createCategoriaInsumoModal" title="Nueva categoría">
                                    <i class="fas fa-plus"></i>
                                </button>
                                <button type="button" class="btn btn-outline-warning btn-edit-categoria-insumo" 
                                        data-id="{{ $item->insumo->id_cat_insumo }}"
                                        data-nombre="{{ $item->insumo->categoria->nombre ?? '' }}"
                                        data-descripcion="{{ $item->insumo->categoria->descripcion ?? '' }}"
                                        title="Editar categoría actual">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </div>
                        </div>
                        @error('id_cat_insumo')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Precio de compra --}}
                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="number" step="0.01" name="precio_compra" id="precio_compra" class="form-control"
                                   value="{{ old('precio_compra', $item->insumo->precio_compra) }}">
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="text-right mt-3">
                <button type="submit" class="btn btn-lg btn-primary">
                    <i class="fas fa-save mr-1"></i> Actualizar Item
                </button>
                <a href="{{ route('items.index') }}" class="btn btn-lg btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

{{-- Modal: Crear/Editar Categoría Producto --}}
@if($item->tipo_item == 'producto')
<div class="modal fade" id="createCategoriaProductoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCategoriaProducto">Nueva Categoría de Producto</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCategoriaProducto">
                @csrf
                <input type="hidden" name="categoria_id" id="cat_producto_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="cat_producto_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="cat_producto_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoriaProducto">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

{{-- Modal: Crear/Editar Categoría Insumo --}}
@if($item->tipo_item == 'insumo')
<div class="modal fade" id="createCategoriaInsumoModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tituloCategoriaInsumo">Nueva Categoría de Insumo</h5>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <form id="formCategoriaInsumo">
                @csrf
                <input type="hidden" name="categoria_id" id="cat_insumo_id">
                <div class="modal-body">
                    <div class="form-group">
                        <label>Nombre <span class="text-danger">*</span></label>
                        <input type="text" name="nombre" id="cat_insumo_nombre" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea name="descripcion" id="cat_insumo_descripcion" class="form-control" rows="2"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="btnGuardarCategoriaInsumo">Guardar</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
$(function() {
    // Input file: mostrar nombre
    $('.custom-file-input').on('change', function() {
        $(this).next('.custom-file-label').html($(this).val().split('\\').pop() || 'Seleccionar archivo');
    });

    // ============================================
    // CREAR/EDITAR CATEGORÍA PRODUCTO
    // ============================================
    $('.btn-edit-categoria-producto').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        
        $('#cat_producto_id').val(id);
        $('#cat_producto_nombre').val(nombre);
        $('#cat_producto_descripcion').val(descripcion);
        $('#tituloCategoriaProducto').text('Editar Categoría de Producto');
        $('#btnGuardarCategoriaProducto').text('Actualizar');
        $('#createCategoriaProductoModal').modal('show');
    });

    $('#createCategoriaProductoModal').on('hidden.bs.modal', function() {
        $('#cat_producto_id').val('');
        $('#cat_producto_nombre').val('');
        $('#cat_producto_descripcion').val('');
        $('#tituloCategoriaProducto').text('Nueva Categoría de Producto');
        $('#btnGuardarCategoriaProducto').text('Guardar');
    });

    $('#formCategoriaProducto').on('submit', function(e) {
        e.preventDefault();
        var id = $('#cat_producto_id').val();
        var url, method;
        
        if (id) {
            url = '/productos/categorias/' + id;
            method = 'PUT';
        } else {
            url = '{{ route("productos.categorias.store") }}';
            method = 'POST';
        }
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(response) {
                $('#createCategoriaProductoModal').modal('hide');
                var cat = response.categoria || response;
                if (id) {
                    $('#id_cat_producto option[value="' + id + '"]').text(cat.nombre || $('#cat_producto_nombre').val());
                } else {
                    var newOption = new Option(cat.nombre || $('#cat_producto_nombre').val(), cat.id_cat_producto, true, true);
                    $('#id_cat_producto').append(newOption);
                }
                toastr.success('Categoría ' + (id ? 'actualizada' : 'creada'));
                $('#formCategoriaProducto')[0].reset();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });

    // ============================================
    // CREAR/EDITAR CATEGORÍA INSUMO
    // ============================================
    $('.btn-edit-categoria-insumo').on('click', function() {
        var id = $(this).data('id');
        var nombre = $(this).data('nombre');
        var descripcion = $(this).data('descripcion');
        
        $('#cat_insumo_id').val(id);
        $('#cat_insumo_nombre').val(nombre);
        $('#cat_insumo_descripcion').val(descripcion);
        $('#tituloCategoriaInsumo').text('Editar Categoría de Insumo');
        $('#btnGuardarCategoriaInsumo').text('Actualizar');
        $('#createCategoriaInsumoModal').modal('show');
    });

    $('#createCategoriaInsumoModal').on('hidden.bs.modal', function() {
        $('#cat_insumo_id').val('');
        $('#cat_insumo_nombre').val('');
        $('#cat_insumo_descripcion').val('');
        $('#tituloCategoriaInsumo').text('Nueva Categoría de Insumo');
        $('#btnGuardarCategoriaInsumo').text('Guardar');
    });

    $('#formCategoriaInsumo').on('submit', function(e) {
        e.preventDefault();
        var id = $('#cat_insumo_id').val();
        var url, method;
        
        if (id) {
            url = '/insumos/categorias/' + id;
            method = 'PUT';
        } else {
            url = '{{ route("insumos.categorias.store") }}';
            method = 'POST';
        }
        
        $.ajax({
            url: url,
            method: method,
            data: $(this).serialize(),
            success: function(response) {
                $('#createCategoriaInsumoModal').modal('hide');
                var cat = response.categoria || response;
                if (id) {
                    $('#id_cat_insumo option[value="' + id + '"]').text(cat.nombre || $('#cat_insumo_nombre').val());
                } else {
                    var newOption = new Option(cat.nombre || $('#cat_insumo_nombre').val(), cat.id_cat_insumo, true, true);
                    $('#id_cat_insumo').append(newOption);
                }
                toastr.success('Categoría ' + (id ? 'actualizada' : 'creada'));
                $('#formCategoriaInsumo')[0].reset();
            },
            error: function(xhr) {
                toastr.error(xhr.responseJSON?.message || 'Error');
            }
        });
    });

    // Expandir sección si hay errores
    @if($errors->any())
        @if($item->tipo_item == 'producto')
            $('#card-producto').removeClass('collapsed-card');
        @else
            $('#card-insumo').removeClass('collapsed-card');
        @endif
    @endif
});
</script>
@endpush