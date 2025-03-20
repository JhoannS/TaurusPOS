<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/admin')->group(function () {
    Route::get('/gastos', function () {
        return Inertia::render('Apps/Essentials/Admin/Gastos/Gastos');
    })->name('essentials.admin.gastos');
});
