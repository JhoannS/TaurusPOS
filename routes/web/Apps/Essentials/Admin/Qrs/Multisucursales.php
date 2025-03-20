<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/admin')->group(function () {
    // Ruta principal
    Route::get('/generadorQrs', function () {
        return Inertia::render('Apps/Essentials/Admin/Qrs/GeneradorQrs');
    })->name('essentials.admin.generadorQrs');
});
