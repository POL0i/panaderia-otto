<?php
// app/Providers/ViewServiceProvider.php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // No inyectes nada en el constructor
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Usar View facade, no inyección de dependencia
        View::composer('*', function ($view) {
            if (Auth::check()) {
                $user = Auth::user();
                $view->with('userPermissions', $user->obtenerPermisos());
                $view->with('userRoles', $user->obtenerRoles());
                $view->with('isAdmin', $user->esAdmin());
                $view->with('currentUser', $user);
            } else {
                // Valores por defecto cuando no hay usuario autenticado
                $view->with('userPermissions', []);
                $view->with('userRoles', []);
                $view->with('isAdmin', false);
                $view->with('currentUser', null);
            }
        });
    }
}