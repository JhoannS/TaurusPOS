<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/cliente')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Apps/Essentials/Cliente/Dashboard');

    })->name('essentials.cliente.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
