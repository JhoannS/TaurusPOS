<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/admin')->group(function () {
    Route::get('/crearItem', function () {
        return Inertia::render('Apps/Essentials/Admin/CrearProducto/Items/CrearItems');
    })->name('essentials.admin.crearItem');
});

