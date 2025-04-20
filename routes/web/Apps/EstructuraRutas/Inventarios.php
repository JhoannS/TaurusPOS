<?php

use App\Http\Controllers\InventariosController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/inventarios', [InventariosController::class, 'index'])
            ->name('aplicacion.inventarios');
    });
});


