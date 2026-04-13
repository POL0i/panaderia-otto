@extends('layouts.adminlte')

@section('title', 'Crear Receta')

@section('content')
<div class="container-fluid">
    
    {{-- Cabecera con estilo panadería --}}
    <div class="row mb-4 animate-fade-in-up">
        <div class="col-md-8">
            <div class="d-flex align-items-center">
                <div class="mr-3">
                    <i class="fas fa-magic fa-2x icon-panaderia"></i>
                </div>
                <div>
                    <h1 class="h3 mb-0">
                        <i class="fas fa-cookie-bite"></i> Crear Nueva Receta
                    </h1>
                    <small class="text-muted">
                        <i class="fas fa-utensils"></i> Ingresa los detalles de tu nueva preparación
                    </small>
                </div>
            </div>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('recetas.index') }}" class="btn btn-back">
                <i class="fas fa-arrow-left"></i> Volver a Recetas
            </a>
        </div>
    </div>

    {{-- Errores de validación --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show shadow-sm animate-fade-in">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <h5><i class="fas fa-exclamation-circle"></i> Por favor corrige los siguientes errores</h5>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Formulario principal --}}
    <div class="card shadow-sm animate-fade-in-up">
        <div class="card-header">
            <h5 class="mb-0">
                <i class="fas fa-pizza-slice"></i> Formulario de Receta
            </h5>
            <small>Completa los campos para crear una nueva receta</small>
        </div>
        
        <form action="{{ route('recetas.store') }}" method="POST">
            @csrf
            <div class="card-body">
                
                {{-- Campo Nombre --}}
                <div class="form-group">
                    <label for="nombre">
                        <i class="fas fa-tag"></i> Nombre de la receta 
                        <span class="text-danger">*</span>
                    </label>
                    <input type="text" 
                           name="nombre" 
                           id="nombre" 
                           class="form-control @error('nombre') is-invalid @enderror" 
                           value="{{ old('nombre') }}" 
                           placeholder="Ej: Pan Francés, Pastel de Chocolate, Galletas de Mantequilla..."
                           required>
                    <small class="form-text">
                        <i class="fas fa-info-circle"></i> Usa un nombre descriptivo y fácil de recordar
                    </small>
                    @error('nombre')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                {{-- Cantidad Requerida --}}
                <div class="form-group">
                    <label for="cantidad_requerida">
                        <i class="fas fa-balance-scale"></i> Cantidad Requerida 
                        <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="number" 
                               name="cantidad_requerida" 
                               id="cantidad_requerida" 
                               step="0.01" 
                               class="form-control @error('cantidad_requerida') is-invalid @enderror" 
                               value="{{ old('cantidad_requerida') }}" 
                               placeholder="Ej: 24"
                               required>
                        <div class="input-group-append">
                            <span class="input-group-text">
                                <i class="fas fa-cookie"></i> unidades
                            </span>
                        </div>
                    </div>
                    <small class="form-text">
                        <i class="fas fa-chart-line"></i> ¿Cuántas unidades produce esta receta?
                    </small>
                    @error('cantidad_requerida')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                {{-- Descripción --}}
                <div class="form-group">
                    <label for="descripcion">
                        <i class="fas fa-align-left"></i> Descripción
                    </label>
                    <textarea name="descripcion" 
                              id="descripcion" 
                              class="form-control @error('descripcion') is-invalid @enderror" 
                              rows="5" 
                              placeholder="Describe el proceso de preparación, ingredientes principales, tiempo de horneado, temperatura, etc...">{{ old('descripcion') }}</textarea>
                    <small class="form-text">
                        <i class="fas fa-lightbulb"></i> Incluye detalles importantes como tiempo de reposo, temperatura, y tips especiales
                    </small>
                    @error('descripcion')<span class="invalid-feedback">{{ $message }}</span>@enderror
                </div>

                {{-- Campo adicional de categoría (opcional pero bonito) --}}
                <div class="form-group">
                    <label for="categoria">
                        <i class="fas fa-folder"></i> Categoría (Opcional)
                    </label>
                    <select name="categoria" id="categoria" class="form-control">
                        <option value="">Selecciona una categoría</option>
                        <option value="pan" {{ old('categoria') == 'pan' ? 'selected' : '' }}>🍞 Panes</option>
                        <option value="pastel" {{ old('categoria') == 'pastel' ? 'selected' : '' }}>🎂 Pasteles</option>
                        <option value="galleta" {{ old('categoria') == 'galleta' ? 'selected' : '' }}>🍪 Galletas</option>
                        <option value="reposteria" {{ old('categoria') == 'reposteria' ? 'selected' : '' }}>🧁 Repostería</option>
                        <option value="salado" {{ old('categoria') == 'salado' ? 'selected' : '' }}>🥐 Salados</option>
                    </select>
                    <small class="form-text">
                        <i class="fas fa-tags"></i> Ayuda a organizar mejor tus recetas
                    </small>
                </div>
            </div>

            {{-- Botones de acción --}}
            <div class="card-footer">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('recetas.index') }}" class="btn btn-cancel">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                    <button type="submit" class="btn btn-save">
                        <i class="fas fa-save"></i> Guardar Receta
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Consejo decorativo --}}
    <div class="row mt-4">
        <div class="col-12">
            <div class="alert alert-info">
                <i class="fas fa-heart"></i>
                <strong>Consejo de panadería:</strong>
                <span>Recuerda que las mejores recetas nacen de la práctica y el amor por lo que haces. ¡Añade tus secretos en la descripción!</span>
            </div>
        </div>
    </div>
</div>
@endsection