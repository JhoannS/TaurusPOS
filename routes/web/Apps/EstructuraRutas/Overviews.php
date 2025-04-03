<?php

use App\Http\Controllers\OverviewsController;
use Illuminate\Support\Facades\Route;



Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        Route::get('/overviews', [OverviewsController::class, 'index'])
            ->name('aplicacion.overviews');
    });
});


