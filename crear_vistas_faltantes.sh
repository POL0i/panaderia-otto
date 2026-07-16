#!/bin/bash
# Script para crear vistas faltantes en construccion

VIEWS=(
    "admin/ventas/confirmar_pago.blade.php"
    "almacen-item/show.blade.php"
    "almacen/show.blade.php"
    "detallecompra/show.blade.php"
    "detalleventa/show.blade.php"
    "insumo/categoria-edit.blade.php"
    "insumo/show.blade.php"
    "item/show.blade.php"
    "notas-venta/create.blade.php"
    "notas-venta/index.blade.php"
    "notaventa/edit.blade.php"
    "pempresa/show.blade.php"
    "ppersona/show.blade.php"
    "produccion/categorias-insumo/index.blade.php"
    "producto/show.blade.php"
    "proveedores/show.blade.php"
    "reportes/compras.blade.php"
    "reportes/ventas.blade.php"
)

for VIEW_PATH in "${VIEWS[@]}"; do
    FULL_PATH="/home/denis/Documentos/panaderia-otto/resources/views/$VIEW_PATH"
    DIR_PATH=$(dirname "$FULL_PATH")
    
    mkdir -p "$DIR_PATH"
    
    cat << 'TEMPLATE' > "$FULL_PATH"
@extends('layouts.adminlte')

@section('title', 'En Construcción')
@section('page-title', 'En Construcción')

@section('content')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center mt-5">
            <div class="card shadow-sm border-panaderia">
                <div class="card-body py-5">
                    <i class="fas fa-tools fa-4x text-panaderia mb-4"></i>
                    <h2 class="font-weight-bold text-panaderia">Página en Construcción</h2>
                    <p class="text-muted mt-3">
                        Esta sección (<strong>{{ request()->path() }}</strong>) se encuentra actualmente en desarrollo y pronto estará disponible.
                    </p>
                    <a href="{{ url()->previous() !== url()->current() ? url()->previous() : route('home') }}" class="btn btn-save mt-4">
                        <i class="fas fa-arrow-left mr-2"></i> Volver Atrás
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
TEMPLATE
    echo "Creado: $VIEW_PATH"
done
