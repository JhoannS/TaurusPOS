<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

$routesPath = __DIR__ . '/web';
foreach (File::allFiles($routesPath) as $routeFile) {
    require_once $routeFile->getPathname();
}


// ✅ Ruta dinámica para redirección por aplicación y rol
Route::get('{aplicacion}/{rol}/dashboard', function ($aplicacion, $rol) {
    return Inertia::render('Apps/' . $aplicacion . '/' . $rol . '/Dashboard/Dashboard');
})->name('aplicacion.dashboard');