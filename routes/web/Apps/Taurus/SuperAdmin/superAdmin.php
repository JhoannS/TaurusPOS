<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;

Route::prefix('TaurusCO/superAdmin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (!Gate::allows('access-role', 4)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $user = auth()->user()->load([
            'rol',
            'tienda',
            'tienda.estado',
            'estado',
            'tipoDocumento'
        ]);

        return Inertia::render('Apps/TaurusCO/SuperAdmin/Dashboard/Dashboard', [
            'auth' => [
                'user' => $user
            ]
        ]);

    })->name('superAdmin.dashboard');
});
