
<?php

use App\Http\Controllers\CrearItemsController;
use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/crearItem', [CrearItemsController::class, 'index'])
            ->name('aplicacion.CrearItems');
    });
});
