<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('guru//cliente')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Cliente/Dashboard');
    })->name('cliente.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
