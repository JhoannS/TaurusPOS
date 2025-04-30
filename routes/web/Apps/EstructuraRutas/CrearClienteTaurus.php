<?php

use App\Http\Controllers\CrearClienteTaurusController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/crearClientes', [CrearClienteTaurusController::class, 'index'])
            ->name('aplicacion.crearClientes');
    });
});


