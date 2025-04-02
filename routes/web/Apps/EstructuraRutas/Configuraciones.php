<?php

use App\Http\Controllers\ConfiguracionesController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/configuraciones', [ConfiguracionesController::class, 'index'])
            ->name('aplicacion.configuraciones');
    });
});


