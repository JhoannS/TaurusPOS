<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


// ✅ Ruta para configuraciones
Route::get('/generadorQrs', function ($aplicacion, $rol) {
    $user = auth()->user();

    if (!Gate::allows('access-role', 1)) {
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {
        return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . 'Qrs/GeneradorQrs');
    }

    abort(404);
})->name('aplicacion.generadorQrs');
