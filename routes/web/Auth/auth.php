<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\TipoDocumentoController;


// Mostrar el formulario de login

Route::prefix('login')->group(function () {
    Route::get('/auth', [LoginController::class, 'show'])->name('login.auth');
    Route::post('/auth', [LoginController::class, 'login'])->name('login.post');
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
});


Route::prefix('register')->group(function () {
    // Mostrar el formulario de registro y cargar los tipos de documento
    Route::get('/auth', [TipoDocumentoController::class, 'index'])->name('register.auth');

    // Ruta para procesar el registro (POST)
    Route::post('/auth', [RegisterController::class, 'register'])->name('register');
});
