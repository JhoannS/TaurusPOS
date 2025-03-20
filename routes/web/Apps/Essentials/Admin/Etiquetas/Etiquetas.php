<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;

Route::prefix('essentials/admin')->group(function () {
    Route::get('/etiquetado', function () {
        return Inertia::render('Apps/Essentials/Admin/Etiquetas/Etiquetas');
    })->name('essentials.admin.etiquetado');
});
