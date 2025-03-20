<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::prefix('essentials/admin')->middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        if (!Gate::allows('access-role', 1)) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        $user = auth()->user();

        // Si el ID de la aplicación es 19, renderiza Essentials
        if ($user->tienda && $user->tienda->id_aplicacion_web == 19) {
            return Inertia::render('Apps/Essentials/Administrador/Dashboard/Dashboard');
        }

        abort(404);
    })->name('essentials.admin.dashboard'); // Asegúrate de que el nombre de la ruta sea 'admin.dashboard'
});
