<?php
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
use Inertia\Inertia;

$routesPath = __DIR__ . '/web';
foreach (File::allFiles($routesPath) as $routeFile) {
    require_once $routeFile->getPathname();
}
Route::get('/login', function () {
    return redirect()->route('login.auth');
})->name('login');
