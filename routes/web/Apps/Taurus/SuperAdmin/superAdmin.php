<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])
            ->name('aplicacion.dashboard');
        // Nueva ruta para obtener los detalles del cliente
        Route::get('/dashboard/detalle/{idCliente}', [DashboardController::class, 'detalle'])
            ->name('aplicacion.dashboard.detalle');
        // Ruta para obtener ususarios sin activar
        Route::get('/clientes-activacion', [DashboardController::class, 'getClientesPorActivacion'])
            ->name('clientes-activacion');
        Route::delete('/clientes/{id}', [DashboardController::class, 'destroy'])->name('clientes.destroy');




    });
});
