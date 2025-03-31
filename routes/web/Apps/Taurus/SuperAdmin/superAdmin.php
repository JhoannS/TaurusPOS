<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::middleware('auth')->group(function () {
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
    });
});


