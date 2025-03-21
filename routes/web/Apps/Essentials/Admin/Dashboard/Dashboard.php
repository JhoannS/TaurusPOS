<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {
        
        // Ruta para el dashboard
        Route::get('/dashboard', function ($aplicacion, $rol) {
            $user = auth()->user();

            if (!Gate::allows('access-role', 1)) {
                abort(403, 'No tienes permisos para acceder a esta sección.');
            }

            if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {
                return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Dashboard/Dashboard', [
                    'auth' => [
                    'user' => $user,
            ]
                ]);
            }

            abort(404);
        })->name('aplicacion.dashboard');

    });
});



