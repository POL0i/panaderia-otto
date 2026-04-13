@extends('layouts.adminlte')

@section('title', 'Editar Proveedor')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-edit icon-panaderia"></i> Editar Proveedor</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('proveedores.index') }}" class="btn btn-back btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Errores de validación</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-pencil-alt"></i> Datos del Proveedor</h5>
        </div>
        <form action="{{ route('proveedores.update', $proveedor->id_proveedor) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="tipo_proveedor"><i class="fas fa-list icon-panaderia"></i> Tipo de Proveedor <span class="text-danger">*</span></label>
                    <select class="form-control @error('tipo_proveedor') is-invalid @enderror" id="tipo_proveedor" name="tipo_proveedor" required onchange="toggleFields()">
                        <option value="persona" {{ old('tipo_proveedor', $proveedor->tipo_proveedor) === 'persona' ? 'selected' : '' }}>Persona</option>
                        <option value="empresa" {{ old('tipo_proveedor', $proveedor->tipo_proveedor) === 'empresa' ? 'selected' : '' }}>Empresa</option>
                    </select>
                    @error('tipo_proveedor')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" id="nombre_field" style="display: none;">
                    <label for="nombre"><i class="fas fa-user icon-panaderia"></i> Nombre</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="nombre" name="nombre" value="{{ old('nombre', $proveedor->persona?->nombre ?? '') }}" placeholder="Nombre del proveedor">
                    @error('nombre')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group" id="razon_social_field" style="display: none;">
                    <label for="razon_social"><i class="fas fa-building icon-panaderia"></i> Razón Social</label>
                    <input type="text" class="form-control @error('razon_social') is-invalid @enderror" id="razon_social" name="razon_social" value="{{ old('razon_social', $proveedor->empresa?->razon_social ?? '') }}" placeholder="Razón social de la empresa">
                    @error('razon_social')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="correo"><i class="fas fa-envelope icon-panaderia"></i> Correo <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('correo') is-invalid @enderror" id="correo" name="correo" value="{{ old('correo', $proveedor->correo) }}" placeholder="ejemplo@email.com" required>
                    @error('correo')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="telefono"><i class="fas fa-phone icon-panaderia"></i> Teléfono <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('telefono') is-invalid @enderror" id="telefono" name="telefono" value="{{ old('telefono', $proveedor->telefono) }}" placeholder="+56 9 1234 5678" required>
                    @error('telefono')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="direccion"><i class="fas fa-map-marker-alt icon-panaderia"></i> Dirección <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('direccion') is-invalid @enderror" id="direccion" name="direccion" value="{{ old('direccion', $proveedor->direccion) }}" placeholder="Calle, número, ciudad" required>
                    @error('direccion')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-check"></i> Actualizar
                </button>
                <a href="{{ route('proveedores.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
<script>
function toggleFields() {
    const tipo = document.getElementById('tipo_proveedor').value;
    document.getElementById('nombre_field').style.display = tipo === 'persona' ? 'block' : 'none';
    document.getElementById('razon_social_field').style.display = tipo === 'empresa' ? 'block' : 'none';
}
toggleFields();
</script>
@endsection
