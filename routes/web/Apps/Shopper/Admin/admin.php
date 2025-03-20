<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('shopper/admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Admin/Dashboard');
    })->name('admin.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
