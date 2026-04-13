<?php

namespace App\Providers;

use App\Helpers\PermisoHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar PermisoHelper como alias global
        $this->app->alias('permiso', PermisoHelper::class);

        // Registrar directivas Blade para permisos
        Blade::if('permiso', function ($permiso) {
            return PermisoHelper::tienePermiso($permiso);
        });

        Blade::if('permisos', function ($permisos) {
            return PermisoHelper::tienePermisos($permisos);
        });

        Blade::if('admin', function () {
            return PermisoHelper::esAdmin();
        });

        Blade::if('puedeVer', function () {
            return PermisoHelper::puedeVer();
        });

        Blade::if('puedeCrear', function () {
            return PermisoHelper::puedeCrear();
        });

        Blade::if('puedeEditar', function () {
            return PermisoHelper::puedeEditar();
        });

        Blade::if('puedeEliminar', function () {
            return PermisoHelper::puedeEliminar();
        });
    }
}

