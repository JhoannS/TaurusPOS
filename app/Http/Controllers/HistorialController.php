<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auditoria;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class HistorialController extends Controller
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
            'tienda.pagosMembresia',
            'estado',
            'tipoDocumento'
        ]);

        // Validar acceso con Gate (rol 4)
        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Verificar que el usuario tenga tienda y la aplicación coincida
        if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {

            $auditorias = Auditoria::with('user')
                ->where('user_id', $user->id)
                ->latest()
                ->take(30)
                ->get()
                ->map(function ($a) {
                    return [
                        'id' => $a->id,
                        'usuario' => $a->user->nombres_ct ?? 'Sistema',
                        'accion' => $a->accion,
                        'accion_normalizada' => str_replace(' ', '_', $a->accion), // 👈 aquí
                        'modelo' => $a->modelo,
                        'modelo_id' => $a->modelo_id,
                        'comentario' => $a->comentario,
                        'ip' => $a->ip,
                        'url' => $a->url,
                        'fecha' => $a->created_at->format('Y-m-d H:i:s'),
                    ];
                });


            return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Historial/Historial', [
                'auth' => ['user' => $user],
                'aplicacion' => $aplicacion,
                'rol' => $rol,
                'auditorias' => $auditorias,
            ]);



        }

        abort(404);
    }

    use AuthorizesRequests;

}
