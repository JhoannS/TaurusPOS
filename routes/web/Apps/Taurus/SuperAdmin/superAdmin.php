<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Gate;


Route::middleware('auth')->group(function () {
    Route::prefix('{aplicacion}/{rol}')->group(function () {

        // Ruta para el dashboard
        Route::get('/dashboard', function ($aplicacion, $rol) {
            $user = auth()->user()->load([
                'rol',
                'tienda',
                'tienda.estado',
                'tienda.aplicacion',
                'tienda.aplicacion.plan',
                'tienda.aplicacion.plan.detalles',
                'tienda.aplicacion.membresia',
                'tienda.aplicacion.membresia.estado',
                'estado',
                'tipoDocumento'
            ]);

            if (!Gate::allows('access-role', 4)) {
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
