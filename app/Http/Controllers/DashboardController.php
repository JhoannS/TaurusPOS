<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // ✅ Importa la clase DB correctamente
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class DashboardController extends Controller
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
            'tienda.token.estado', // Cargar directamente el estado desde el token
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


            $totalPrecio = $clientes->sum('precio');

            return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Dashboard/Dashboard', [
                'auth' => ['user' => $user],
                'clientes' => $clientes,
                'totalPrecio' => $totalPrecio ?: 0, // Asegurar que no sea NULL
                'aplicacion' => $aplicacion,
                'rol' => $rol,
                'user' => $user->load('tienda.aplicacion.plan.detalles')
            ]);


        }

        abort(404);
    }

    // Agrega este método al DashboardController
    public function detalle($aplicacion, $rol, $idCliente)
    {
        // Opcional: valida permisos (rol 4, etc.)
        $user = auth()->user()->load([
            'rol',
            'tienda',
            'tienda.aplicacion',
        ]);
        if (!Gate::allows('access-role', 4) || $user->rol->id != 4) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        // Cargar el cliente con todas las relaciones necesarias
        $detalleCliente = ClienteTaurus::with([
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
            'tipoDocumento',
            'membresia'
        ])->findOrFail($idCliente);

        // Retornar en JSON (para usar en el modal)
        return response()->json($detalleCliente);
    }
    public function getClientesPorActivacion($aplicacion, $rol)
    {
        $clientes = ClienteTaurus::select(
            'clientes_taurus.id',
            'clientes_taurus.nombres_ct',
            'clientes_taurus.apellidos_ct',
            'tiendas_sistematizadas.nombre_tienda'
        )
            ->join('tiendas_sistematizadas', 'clientes_taurus.id_tienda', '=', 'tiendas_sistematizadas.id')
            ->join('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
            ->where('token_accesos.id_estado', 2) // ✅ Filtrar por id_estado = 2
            ->get();

        return response()->json($clientes);
    }



    use AuthorizesRequests; // 👈 Añadir este trait aquí


    public function destroy(Request $request, $aplicacion, $rol, $id)
    {
        $cliente = ClienteTaurus::with('rol')->find($id); // ✅ Cargar relación 'rol'

        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        // ✅ Guardar el nombre del rol antes de eliminarlo
        $nombreRol = $cliente->rol ? $cliente->rol->nombre_rol : 'SuperAdmin';

        // ✅ Si la tienda tiene un token asociado, elimínalo primero
        if ($cliente->tienda && $cliente->tienda->token) {
            $cliente->tienda->token()->delete();
        }

        // ✅ Eliminar la tienda asociada (si existe)
        if ($cliente->tienda) {
            $cliente->tienda()->delete();
        }

        // ✅ Eliminar relaciones dependientes
        if ($cliente->estado) {
            $cliente->estado()->dissociate()->delete();
        }

        if ($cliente->tipoDocumento) {
            $cliente->tipoDocumento()->dissociate()->delete();
        }

        if ($cliente->rol) {
            $cliente->rol()->dissociate()->delete();
        }

        // ✅ Finalmente eliminar el cliente
        $cliente->deleteOrFail();

        // ✅ Si no hay rol, usar valor por defecto 'Invitado'
        $nombreRol = $nombreRol ?: 'SuperAdmin';

        // ✅ Redirigir correctamente con el valor del rol
        return redirect()->route('aplicacion.dashboard', [
            'aplicacion' => $aplicacion,
            'rol' => ucfirst($nombreRol) // ✅ Usa el nombre del rol
        ]);
    }






}
