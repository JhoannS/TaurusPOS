<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('shopper/empleado')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Empleado/Dashboard');
    })->name('empleado.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
