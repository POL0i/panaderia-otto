<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-db', function () {  
    try {
        DB::connection()->getPdo();
        return '✅ Conexión exitosa a la base de datos: ' . DB::connection()->getDatabaseName();
    } catch (\Exception $e) {
        return '❌ Error de conexión: ' . $e->getMessage();
    }
});