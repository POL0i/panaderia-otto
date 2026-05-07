@extends('layouts.adminlte')

@section('title', 'Crear Producto')
@section('page-title', 'Crear Producto')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Producto</h3>
            </div>
            <form action="{{ route('productos.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- ❌ ELIMINADO: Select de items -->

                    <div class="form-group">
                        <label for="nombre">Nombre del Producto <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                        <select class="form-control @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" required>
                            <option value="">Seleccione una unidad</option>
                            <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                            <option value="docena" {{ old('unidad_medida') == 'docena' ? 'selected' : '' }}>Docena</option>
                            <option value="paquete" {{ old('unidad_medida') == 'paquete' ? 'selected' : '' }}>Paquete</option>
                            <option value="bandeja" {{ old('unidad_medida') == 'bandeja' ? 'selected' : '' }}>Bandeja</option>
                            <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                            <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                            <option value="L" {{ old('unidad_medida') == 'L' ? 'selected' : '' }}>Litro (L)</option>
                        </select>
                        @error('unidad_medida')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_cat_producto">Categoría <span class="text-danger">*</span></label>
                        <select class="form-control @error('id_cat_producto') is-invalid @enderror" id="id_cat_producto" name="id_cat_producto" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_producto }}" {{ old('id_cat_producto') == $categoria->id_cat_producto ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_producto')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="precio">Precio <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" class="form-control @error('precio') is-invalid @enderror" id="precio" name="precio" value="{{ old('precio') }}" required>
                        @error('precio')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="imagen">Imagen del Producto</label>
                        <input type="file" class="form-control-file @error('imagen') is-invalid @enderror" id="imagen" name="imagen" accept="image/*">
                        <small class="text-muted">Formatos: JPG, PNG, GIF (Max: 2MB)</small>
                        @error('imagen')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="imagen_url">O URL de la imagen</label>
                        <input type="url" class="form-control @error('imagen_url') is-invalid @enderror" id="imagen_url" name="imagen_url" placeholder="https://ejemplo.com/imagen.jpg">
                        @error('imagen_url')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('productos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
