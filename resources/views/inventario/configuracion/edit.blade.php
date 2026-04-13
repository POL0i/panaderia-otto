@extends('layouts.adminlte')

@section('title', 'Configuración de Inventario')

@section('content')
<div class="container-fluid">
    <div class="row mb-3 animate-fade-in-up">
        <div class="col-md-6">
            <h1 class="h3 mb-0"><i class="fas fa-cog icon-panaderia"></i> Configuración de Inventario</h1>
        </div>
        <div class="col-md-6 text-right">
            <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
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

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0"><i class="fas fa-sliders-h"></i> Parámetros del Sistema</h5>
        </div>
        <form action="{{ route('configuracion.update') }}" method="POST">
            @csrf
            @method('PUT')
            <div class="card-body">
                <div class="form-group">
                    <label for="metodo_valuacion_predeterminado"><i class="fas fa-list icon-panaderia"></i> Método de Valuación Predeterminado <span class="text-danger">*</span></label>
                    <select name="metodo_valuacion_predeterminado" id="metodo_valuacion_predeterminado" class="form-control @error('metodo_valuacion_predeterminado') is-invalid @enderror">
                        <option value="PEPS" {{ $config->metodo_valuacion_predeterminado == 'PEPS' ? 'selected' : '' }}>
                            PEPS (Primero en Entrar, Primero en Salir)
                        </option>
                        <option value="UEPS" {{ $config->metodo_valuacion_predeterminado == 'UEPS' ? 'selected' : '' }}>
                            UEPS (Último en Entrar, Primero en Salir)
                        </option>
                    </select>
                    <small class="form-text text-muted">
                        Este método se utilizará por defecto al crear nuevos lotes.
                    </small>
                    @error('metodo_valuacion_predeterminado')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                <hr>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="automatizar_movimientos" id="automatizar_movimientos" class="custom-control-input" value="1" {{ $config->automatizar_movimientos ? 'checked' : '' }}>
                        <label class="custom-control-label" for="automatizar_movimientos">
                            <strong><i class="fas fa-magic icon-panaderia"></i> Automatizar Movimientos</strong>
                        </label>
                    </div>
                    <small class="form-text text-muted" style="display: block; margin-top: 5px;">
                        Si está habilitado, el sistema creará automáticamente movimientos de inventario cuando se registren compras, ventas o producciones.
                    </small>
                </div>

                <div class="form-group">
                    <div class="custom-control custom-checkbox">
                        <input type="checkbox" name="requerir_aprobacion" id="requerir_aprobacion" class="custom-control-input" value="1" {{ $config->requerir_aprobacion ? 'checked' : '' }}>
                        <label class="custom-control-label" for="requerir_aprobacion">
                            <strong><i class="fas fa-check-square icon-panaderia"></i> Requerir Aprobación</strong>
                        </label>
                    </div>
                    <small class="form-text text-muted" style="display: block; margin-top: 5px;">
                        Si está habilitado, los movimientos y traspasos necesitarán aprobación antes de ser completados.
                    </small>
                </div>

                <hr>

                <div class="alert alert-info animate-fade-in">
                    <h6 class="font-weight-bold"><i class="fas fa-info-circle"></i> Información del Sistema</h6>
                    <ul class="mb-0">
                        <li><strong>Última actualización:</strong> {{ $config->updated_at->format('d/m/Y H:i') }}</li>
                        <li><strong>Creado:</strong> {{ $config->created_at->format('d/m/Y H:i') }}</li>
                    </ul>
                </div>
            </div>
            <div class="card-footer d-flex justify-content-between">
                <button type="submit" class="btn btn-save btn-sm">
                    <i class="fas fa-save"></i> Guardar Cambios
                </button>
                <a href="{{ route('movimientos.index') }}" class="btn btn-back btn-sm">
                    <i class="fas fa-arrow-left"></i> Volver
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
