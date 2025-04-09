<?php

use App\Http\Controllers\EditarClienteTaurusController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/editarClienteTaurus', [EditarClienteTaurusController::class, 'index'])
            ->name('aplicacion.aplicacion.cliente.taurus.');
            
        Route::get('/editarClienteTaurus/{id}', [EditarClienteTaurusController::class, 'editar'])
            ->name('aplicacion.editarClienteTaurus.id');

    });
});

