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
