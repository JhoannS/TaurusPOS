<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/admin')->group(function () {
    // Ruta principal
    Route::get('/multisucursales', function () {
        return Inertia::render('Apps/Essentials/Admin/Multisucursales/Multisucursales');
    })->name('essentials.admin.multisucursales');

    // Subruta relacionada con multisucursales
    Route::get('/infoSucursal', function () {
        return Inertia::render('Apps/Essentials/Admin/Multisucursales/InfoSucursales');
    })->name('essentials.admin.infoSucursal');
});
