<?php

use App\Http\Controllers\EditarClienteTaurusController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/editarClienteTaurus', [EditarClienteTaurusController::class, 'index'])
            ->name('aplicacion.clientesTaurus');

        Route::get('/editarClienteTaurus/{id}', [EditarClienteTaurusController::class, 'editar'])
            ->name('aplicacion.editarClienteTaurus.id');

        Route::put('/editarClienteTaurus/{id}', [EditarClienteTaurusController::class, 'actualizar'])
            ->name('aplicacion.editarClienteTaurus.actualizar');

            Route::get('/dashboard/detalle/{idCliente}', [EditarClienteTaurusController::class, 'detalleUser'])
            ->name('aplicacion.editarClienteTaurus.detalle');
    });

});

