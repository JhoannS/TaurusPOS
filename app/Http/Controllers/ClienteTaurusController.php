<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use Inertia\Inertia;

class ClienteTaurusController extends Controller
{
    public function index()
    {
        $clientes = ClienteTaurus::with([
            'rol',
            'tienda',
            'tienda.estado',
            'tienda.aplicacion',
            'estado',
            'tipoDocumento'
        ])->get()->map(function ($cliente) {
            return [
                'id' => $cliente->id,
                'nombre' => $cliente->getFullNameAttribute(),
                'tienda' => $cliente->tienda?->nombre_tienda,
                'telefono' => $cliente->telefono_ct,
                'token' => $cliente->estado?->nombre_estado ?? 'Sin estado',
                'aplicacion' => $cliente->tienda?->aplicacion?->nombre_app ?? 'Sin aplicación',
                'membresia' => $cliente->tienda?->aplicacion?->membresia?->nombre_membresia ?? 'Sin membresía',
                'precio' => $cliente->tienda?->aplicacion?->plan?->precio ?? 0,
                'estado' => $cliente->estado?->nombre_estado ?? 'Sin estado',
            ];
        });

        return Inertia::render('Apps/Essentials/Admin/Dashboard/Dashboard', [
            'clientes' => $clientes
        ]);
    }
}
