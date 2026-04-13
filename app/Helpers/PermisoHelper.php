<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class PermisoHelper
{
    /**
     * Verificar si el usuario autenticado tiene un permiso
     */
    public static function tienePermiso($permiso)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->tienePermiso($permiso);
    }

    /**
     * Verificar si el usuario es administrador
     */
    public static function esAdmin()
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->esAdmin();
    }

    /**
     * Verificar si el usuario tiene múltiples permisos (AND)
     */
    public static function tienePermisos($permisos)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->tienePermisos($permisos);
    }

    /**
     * Verificar si el usuario tiene al menos uno de varios permisos (OR)
     */
    public static function tieneAlgunPermiso($permisos)
    {
        if (!Auth::check()) {
            return false;
        }

        return Auth::user()->tieneAlgunPermiso($permisos);
    }

    /**
     * Obtener todos los permisos del usuario
     */
    public static function obtenerPermisos()
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user()->obtenerPermisos();
    }

    /**
     * Obtener todos los roles del usuario
     */
    public static function obtenerRoles()
    {
        if (!Auth::check()) {
            return [];
        }

        return Auth::user()->obtenerRoles();
    }

    /**
     * Verificar si puede ver
     */
    public static function puedeVer()
    {
        return self::tienePermiso('ver');
    }

    /**
     * Verificar si puede crear
     */
    public static function puedeCrear()
    {
        return self::tienePermiso('crear');
    }

    /**
     * Verificar si puede editar
     */
    public static function puedeEditar()
    {
        return self::tienePermiso('editar');
    }

    /**
     * Verificar si puede eliminar
     */
    public static function puedeEliminar()
    {
        return self::tienePermiso('eliminar');
    }
}
