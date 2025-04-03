<?php

use App\Http\Controllers\MultisucursalController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/multisucursales', [MultisucursalController::class, 'index'])
            ->name('aplicacion.multisucursales');
    });
});


