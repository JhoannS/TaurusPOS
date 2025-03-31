<?php

use Inertia\Inertia;
use Illuminate\Support\Facades\Route;


Route::fallback(function() { 
    return Inertia::render('Errors/Error404', ['status' => 404]);
});
