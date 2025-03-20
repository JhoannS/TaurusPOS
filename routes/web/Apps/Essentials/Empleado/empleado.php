<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/empleado')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Apps/Essentials/Empleado/Dashboard');
    })->name('essentials.empleado.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
