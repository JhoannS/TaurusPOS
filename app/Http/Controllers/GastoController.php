<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class GastoController extends Controller
{
    /**
     * Muestra el dashboard para la aplicación y rol especificados.
     *
     * @param  string  $aplicacion
     * @param  string  $rol
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index($aplicacion, $rol, Request $request)
    {
        // Cargar relaciones necesarias del usuario autenticado
        $user = auth()->user()->load([
            'rol',
            'tienda',
            'tienda.token',
            'tienda.token.estado',
            'tienda.estado',
            'tienda.aplicacion',
            'tienda.aplicacion.plan',
            'tienda.aplicacion.plan.detalles',
            'tienda.aplicacion.membresia',
            'tienda.aplicacion.membresia.estado',
            'estado',
            'tipoDocumento'
        ]);

        // Validar acceso con Gate (rol 4)
        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Verificar que el usuario tenga tienda y la aplicación coincida
        if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {


            return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Gastos/Gastos', [
                'auth' => ['user' => $user],
                'aplicacion' => $aplicacion,
                'rol' => $rol,
            ]);



        }

        abort(404);
    }

    use AuthorizesRequests;

}
