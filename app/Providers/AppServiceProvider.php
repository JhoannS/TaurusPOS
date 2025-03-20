<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Inertia\Inertia; // 👉 Asegúrate de que esta línea esté presente
use App\Models\ClienteTaurus;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerPolicies();

        // ✅ Definir el Gate para roles
        Gate::define('access-role', function (ClienteTaurus $user, $role) {
            return $user->id_rol == $role;
        });

        Inertia::share([
            'auth' => function () {
                if (Auth::check()) {
                    // ✅ Cargar todas las relaciones correctamente
                    $user = Auth::user()->load('rol', 'estado', 'tienda.estado');

                    return [
                        'user' => [
                            'id' => $user->id,
                            'nombres_ct' => $user->nombres_ct,
                            'apellidos_ct' => $user->apellidos_ct,
                            'email_ct' => $user->email_ct,
                            'telefono_ct' => $user->telefono_ct,
                            'rol' => $user->rol ? [
                                'id' => $user->rol->id,
                                'nombre' => $user->rol->nombre
                            ] : null,
                            'estado' => $user->estado ? [
                                'id' => $user->estado->id,
                                'nombre' => $user->estado->tipo_estado 
                            ] : null,
                            'tienda' => $user->tienda ? [
                                'nombre_tienda' => $user->tienda->nombre_tienda,
                                'email_tienda' => $user->tienda->email_tienda,
                                'telefono_tienda' => $user->tienda->telefono_ct,
                                'direccion' => $user->tienda->direccion_ct,
                                'barrio' => $user->tienda->barrio_ct,
                                'estado' => $user->tienda->estado ? [
                                    'id' => $user->tienda->estado->id,
                                    'nombre' => $user->tienda->estado->tipo_estado
                                ] : null,
                                'created_at' => $user->tienda->created_at,
                                'logo_tienda' => $user->tienda->logo_tienda
                                    ? url('storage/' . $user->tienda->logo_tienda)
                                    : null
                            ] : null
                        ]
                    ];
                }
                return null;
            },
        ]);
    }
}
