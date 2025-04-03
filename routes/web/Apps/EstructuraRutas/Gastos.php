<?php

use App\Http\Controllers\GastoController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/gastos', [GastoController::class, 'index'])
            ->name('aplicacion.gastos');
    });
});


