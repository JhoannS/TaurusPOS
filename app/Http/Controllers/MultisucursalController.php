<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class MultisucursalController extends Controller
{
    public function index()
    {
        return Inertia::render('Apps/Essentials/Administrador/Multisucursales', [
            'currentRoute' => 'multisucursales'
        ]);
    }
}
