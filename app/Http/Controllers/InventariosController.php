<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB; // ✅ Importa la clase DB correctamente
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class InventariosController extends Controller
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
            'tienda.pagosMembresia',  // Nota que "pagosMembresia" está en singular
            'estado',
            'tipoDocumento'
        ]);

        // Validar acceso con Gate (rol 4)
        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Verificar que el usuario tenga tienda y la aplicación coincida
        if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {

            // Consulta principal con joins (alias renombrados para evitar conflictos con relaciones cargadas)
            $clientes = ClienteTaurus::select(
                'clientes_taurus.id',
                \DB::raw("CONCAT(clientes_taurus.nombres_ct, ' ', clientes_taurus.apellidos_ct) AS nombre_completo"),
                'clientes_taurus.telefono_ct as telefono',
                \DB::raw('COALESCE(tiendas_sistematizadas.nombre_tienda, "Sin tienda") as nombre_tienda'),
                'token_accesos.token_activacion as token',
                'aplicaciones_web.nombre_app as aplicacion',
                'membresias.nombre_membresia as membresia',
                \DB::raw('IFNULL(membresias.precio, 0) as precio'), // ✅ Reemplazo aquí
                \DB::raw('COALESCE(estados.tipo_estado, "Sin estado") as estado_tipo'),
                \DB::raw('COALESCE(token_estado.tipo_estado, "Sin estado") as estado_token'),
                'clientes_taurus.fecha_creacion',

                // ✅ Estado, monto y fecha desde pagos_membresia
                'pagos_membresia.monto_total as monto_pago',
                'pagos_membresia.fecha_pago as fecha_pago',
                'estado_pago.tipo_estado as estado_pago'

            )
                ->leftJoin('tiendas_sistematizadas', 'clientes_taurus.id_tienda', '=', 'tiendas_sistematizadas.id')
                ->leftJoin('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
                ->leftJoin('aplicaciones_web', 'tiendas_sistematizadas.id_aplicacion_web', '=', 'aplicaciones_web.id')
                ->leftJoin('membresias', 'aplicaciones_web.id_membresia', '=', 'membresias.id')
                ->leftJoin('estados', 'clientes_taurus.id_estado', '=', 'estados.id')
                ->leftJoin('estados as token_estado', 'token_accesos.id_estado', '=', 'token_estado.id')

                // ✅ JOIN para los pagos de membresía
                ->leftJoin('pagos_membresia', 'clientes_taurus.id', '=', 'pagos_membresia.id_cliente')
                ->leftJoin('estados as estado_pago', 'pagos_membresia.id_estado', '=', 'estado_pago.id')
                ->orderBy('clientes_taurus.fecha_creacion', 'DESC')
                ->get();


          

            return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Inventarios/Inventarios', [
                'auth' => ['user' => $user],
                'clientes' => $clientes,
                'aplicacion' => $aplicacion,
                'rol' => $rol,
            ]);
            


        }

        abort(404);
    }

    use AuthorizesRequests;

}
