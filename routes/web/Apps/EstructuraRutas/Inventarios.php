<?php

use App\Http\Controllers\InventariosController;
use App\Http\Controllers\InfoProductosController;
use App\Http\Controllers\InfoCategoriasController;
use App\Http\Controllers\InfoServiciosController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/inventarios', [InventariosController::class, 'index'])
            ->name('aplicacion.inventarios');
        
            Route::get('/inventarios/infoProductos', [InfoProductosController::class, 'index'])
            ->name('aplicacion.infoProductos');

            Route::get('/inventarios/infoCategorias', [InfoCategoriasController::class, 'index'])
            ->name('aplicacion.infoCategorias');

            Route::get('/inventarios/infoServicios', [InfoServiciosController::class, 'index'])
            ->name('aplicacion.infoServicios');
    });
});


