@extends('layouts.adminlte')

@section('title', 'Crear Insumo')
@section('page-title', 'Crear Insumo')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Formulario de Insumo</h3>
            </div>
            <form action="{{ route('insumos.store') }}" method="POST" class="form-horizontal" enctype="multipart/form-data">
                @csrf
                <div class="card-body">
                    <!-- ❌ ELIMINADO: Select de items -->

                    <div class="form-group">
                        <label for="nombre">Nombre del Insumo <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre') }}" required>
                        @error('nombre')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="unidad_medida">Unidad de Medida <span class="text-danger">*</span></label>
                        <select class="form-control @error('unidad_medida') is-invalid @enderror" id="unidad_medida" name="unidad_medida" required>
                        <option value="">Seleccione una unidad</option>
                        <option value="kg" {{ old('unidad_medida') == 'kg' ? 'selected' : '' }}>Kilogramo (kg)</option>
                       <option value="g" {{ old('unidad_medida') == 'g' ? 'selected' : '' }}>Gramo (g)</option>
                      <option value="lb" {{ old('unidad_medida') == 'lb' ? 'selected' : '' }}>Libra (lb)</option>
                       <option value="oz" {{ old('unidad_medida') == 'oz' ? 'selected' : '' }}>Onza (oz)</option>
                        <option value="L" {{ old('unidad_medida') == 'L' ? 'selected' : '' }}>Litro (L)</option>
                        <option value="mL" {{ old('unidad_medida') == 'mL' ? 'selected' : '' }}>Mililitro (mL)</option>
                         <option value="unidad" {{ old('unidad_medida') == 'unidad' ? 'selected' : '' }}>Unidad</option>
                         <option value="docena" {{ old('unidad_medida') == 'docena' ? 'selected' : '' }}>Docena</option>
</select>
                        @error('unidad_medida')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="id_cat_insumo">Categoría <span class="text-danger">*</span></label>
                        <select class="form-control @error('id_cat_insumo') is-invalid @enderror" id="id_cat_insumo" name="id_cat_insumo" required>
                            <option value="">Seleccione una categoría</option>
                            @foreach ($categorias as $categoria)
                                <option value="{{ $categoria->id_cat_insumo }}" {{ old('id_cat_insumo') == $categoria->id_cat_insumo ? 'selected' : '' }}>
                                    {{ $categoria->nombre }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_cat_insumo')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="precio_compra">Precio de Compra</label>
                        <input type="number" step="0.01" class="form-control @error('precio_compra') is-invalid @enderror" id="precio_compra" name="precio_compra" value="{{ old('precio_compra') }}">
                        @error('precio_compra')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Guardar</button>
                    <a href="{{ route('insumos.index') }}" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
