<?php

use App\Http\Controllers\HistorialController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/historial', [HistorialController::class, 'index'])
            ->name('aplicacion.historial');
    });
});


