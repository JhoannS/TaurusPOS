<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use App\Models\TiendaSistematizada;
use App\Models\Estados;
use App\Models\Membresia;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;


class EditarClienteTaurusController extends Controller
{
    /**
     * Muestra el dashboard para la aplicación y rol especificados.
     *
     * @param  string  $aplicacion
     * @param  string  $rol
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */


    public function editar($aplicacion, $rol, $id)
    {
        $user = auth()->user();

        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403);
        }

        // Consulta del cliente por ID
        $cliente = ClienteTaurus::select(
            'clientes_taurus.id',
            DB::raw("CONCAT(clientes_taurus.nombres_ct, ' ', clientes_taurus.apellidos_ct) AS nombre_completo"),
            'clientes_taurus.nombres_ct',
            'clientes_taurus.apellidos_ct',
            'clientes_taurus.telefono_ct as telefono',
            'clientes_taurus.id_tienda',
            'clientes_taurus.id_estado',

            DB::raw('COALESCE(tiendas_sistematizadas.nombre_tienda, "Sin tienda") as nombre_tienda'),
            'token_accesos.token_activacion as token',
            'aplicaciones_web.id as id_aplicacion',
            'aplicaciones_web.nombre_app as aplicacion',
            'membresias.id as id_membresia',
            'membresias.nombre_membresia as membresia',
            DB::raw('IFNULL(membresias.precio, 0) as precio'),
            DB::raw('COALESCE(estados.tipo_estado, "Sin estado") as estado_tipo'),
            DB::raw('COALESCE(token_estado.tipo_estado, "Sin estado") as estado_token'),
            'clientes_taurus.fecha_creacion',

            // Datos de pago
            'pagos_membresia.id_estado as id_estado_pago',
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
            ->leftJoin('pagos_membresia', 'clientes_taurus.id', '=', 'pagos_membresia.id_cliente')
            ->leftJoin('estados as estado_pago', 'pagos_membresia.id_estado', '=', 'estado_pago.id')
            ->where('clientes_taurus.id', $id)
            ->first();

        if (!$cliente) {
            abort(404);
        }

        // Catálogos
        $tiendas = TiendaSistematizada::all();
        $membresias = Membresia::all();
        $estados = Estados::all();

        return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/EditarClienteTaurus/EditarClienteTaurus', [
            'auth' => ['user' => $user],
            'cliente' => $cliente,
            'tiendas' => $tiendas,
            'membresias' => $membresias,
            'estados' => $estados,
            'aplicacion' => $aplicacion,
            'rol' => $rol,
        ]);
    }

    use AuthorizesRequests;

}
