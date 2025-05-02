<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {

    // Ruta para polling (sin parámetros)
    Route::get('/listar-clientes', [DashboardController::class, 'listarClientesSinParametros']);

    Route::post('/status', [DashboardController::class, 'status']);

    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('aplicacion.dashboard');


        Route::get('/dashboard/detalle/{idCliente}', [DashboardController::class, 'detalle'])
            ->name('aplicacion.dashboard.detalle');

        // ✅ Nueva ruta para clientes sin activar
        Route::get('/dashboard/clientes-por-activacion', [DashboardController::class, 'getClientesPorActivacion'])
            ->name('clientes.activacion');

        // ✅ Ruta para eliminar cliente
        Route::delete('/clientes/{id}', [DashboardController::class, 'destroy'])
            ->name('clientes.destroy');

        // ✅ Ruta para actualizar cliente    
        Route::put('/clientes/{id}', [DashboardController::class, 'update'])->name('clientes.update');

        // ✅ Ruta para dinero activo (con prefijo de aplicación y rol)
        Route::get('/dashboard/dinero-activo', [DashboardController::class, 'getDineroActivo'])
            ->name('dinero.activo');

        // Route::get('/clientes/lista', [DashboardController::class, 'listarClientes'])
        //     ->name('clientes.lista');
    });
});


