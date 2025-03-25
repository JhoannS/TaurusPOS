<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TipoDocumentoController;
use App\Http\Controllers\AplicacionWebController;


// Mostrar el formulario de login

Route::prefix('login')->group(function () {
    Route::get('/auth', [LoginController::class, 'show'])->name('login.auth');
    Route::post('/auth', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});

Route::prefix('register')->group(function () {
     // ✅ Ruta GET para mostrar el formulario de registro
     Route::get('/auth', [RegisterController::class, 'show'])->name('register.auth');

    Route::post('/auth', [RegisterController::class, 'register'])->name('register.post');
});
