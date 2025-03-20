<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('guru/admin')->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Apps/Guru/Admin/Dashboard');

    })->name('guru.admin.dashboard');
});

Route::get('/swap', function () {
    return Inertia::render('Swap');
})->name('swap');
