<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // ✅ Importa la clase DB correctamente
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\Traits\RegistraAuditoria; // 👈 Importa el trait correctamente aquí



class DashboardController extends Controller
{
    use RegistraAuditoria; // 👈 Usa el trait aquí a nivel de clase

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
                'estado_pago.tipo_estado as estado_pago',

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
                'totalPrecio' => $totalPrecio ?: 'Sin precio',
                'aplicacion' => $aplicacion,
                'rol' => $rol,
                'detalles' => $user->tienda->aplicacion->plan->detalles, // 🔥 Esta línea soluciona el warning
            ]);



        }

        abort(404);
    }


    public function listarClientes($aplicacion, $rol)
    {
        $user = auth()->user()->load(['rol', 'tienda.aplicacion']);

        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403, 'No tienes permisos para acceder a esta sección.');
        }

        if ($user->tienda && $user->tienda->aplicacion->nombre_app === $aplicacion) {
            $clientes = ClienteTaurus::select(
                'clientes_taurus.id',
                \DB::raw("CONCAT(clientes_taurus.nombres_ct, ' ', clientes_taurus.apellidos_ct) AS nombre_completo"),
                'clientes_taurus.telefono_ct as telefono',
                \DB::raw('COALESCE(tiendas_sistematizadas.nombre_tienda, "Sin tienda") as nombre_tienda'),
                'token_accesos.token_activacion as token',
                'aplicaciones_web.nombre_app as aplicacion',
                'membresias.nombre_membresia as membresia',
                \DB::raw('IFNULL(membresias.precio, 0) as precio'),
                \DB::raw('COALESCE(estados.tipo_estado, "Sin estado") as estado_tipo'),
                \DB::raw('COALESCE(token_estado.tipo_estado, "Sin estado") as estado_token'),
                'clientes_taurus.fecha_creacion',
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
                ->orderBy('clientes_taurus.fecha_creacion', 'DESC')
                ->get();

            return response()->json($clientes);
        }

        return response()->json([], 403);
    }

    // Este método es para polling sin parámetros dinámicos
public function listarClientesSinParametros()
{
    $user = auth()->user()->load(['rol', 'tienda.aplicacion']);

    if (!in_array($user->rol->id, [1, 2, 3, 4])) {
        abort(403, 'No tienes permisos para acceder a esta sección.');
    }

    if ($user->tienda && $user->tienda->aplicacion) {
        $clientes = ClienteTaurus::select(
            'clientes_taurus.id',
            \DB::raw("CONCAT(clientes_taurus.nombres_ct, ' ', clientes_taurus.apellidos_ct) AS nombre_completo"),
            'clientes_taurus.telefono_ct as telefono',
            \DB::raw('COALESCE(tiendas_sistematizadas.nombre_tienda, "Sin tienda") as nombre_tienda'),
            'token_accesos.token_activacion as token',
            'aplicaciones_web.nombre_app as aplicacion',
            'membresias.nombre_membresia as membresia',
            \DB::raw('IFNULL(membresias.precio, 0) as precio'),
            \DB::raw('COALESCE(estados.tipo_estado, "Sin estado") as estado_tipo'),
            \DB::raw('COALESCE(token_estado.tipo_estado, "Sin estado") as estado_token'),
            'clientes_taurus.fecha_creacion',
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
            ->orderBy('clientes_taurus.fecha_creacion', 'DESC')
            ->get();

        return response()->json($clientes);
    }

    return response()->json([], 403);
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


     // Método que retorna si hay cambios desde el último cliente
     public function status(Request $request)
     {
         $lastId = $request->input('lastId', 0); // ID que envía el frontend
 
         $cliente = ClienteTaurus::latest('id')->first();
 
         if ($cliente && $cliente->id > $lastId) {
             return response()->json([
                 'update' => true,
                 'newId' => $cliente->id,
             ]);
         }
 
         return response()->json([
             'update' => false,
         ]);
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
            'tienda.pagosMembresia',  // Nota que "pagosMembresia" está en singular
            'estado',
            'tipoDocumento',
            'membresia'
        ])->findOrFail($idCliente);

        // Retornar en JSON (para usar en el modal)
        return response()->json($detalleCliente);
    }


    

    public function getDineroActivo()
    {
        $dineroActivo = DB::table('pagos_membresia')
            ->where('id_estado', 8) // Estado activo
            ->where('dias_restantes', '>', 0) // Opcional: solo los que aún están vigentes
            ->sum('monto_total'); // Asegúrate que este campo exista

        return response()->json([
            'total_activo' => ($dineroActivo),
        ]);
    }


    use AuthorizesRequests;


    public function destroy(Request $request, $aplicacion, $rol, $id)
    {
        $cliente = ClienteTaurus::with([
            'rol',
            'tienda.token',
            'tienda.pagosMembresia',
            'tienda.membresia.detallesPlan'
        ])->find($id);

        if (!$cliente) {
            abort(404, 'Cliente no encontrado');
        }

        $nombreRol = $cliente->rol ? $cliente->rol->nombre_rol : 'SuperAdmin';

        // ✅ Eliminar relaciones en cascada
        if ($cliente->tienda) {
            if ($cliente->tienda->pagosMembresia) {
                $cliente->tienda->pagosMembresia()->delete();
            }
            if ($cliente->tienda->token) {
                $cliente->tienda->token()->delete();
            }
            if ($cliente->tienda->membresia) {
                if ($cliente->tienda->membresia->detallesPlan) {
                    $cliente->tienda->membresia->detallesPlan()->delete();
                }
                $cliente->tienda->membresia()->delete();
            }
            $cliente->tienda()->delete();
        }

        // ✅ Eliminar relaciones directas del cliente
        if ($cliente->estado) {
            $cliente->estado()->dissociate()->delete();
        }
        if ($cliente->tipoDocumento) {
            $cliente->tipoDocumento()->dissociate()->delete();
        }
        if ($cliente->rol) {
            $cliente->rol()->dissociate()->delete();
        }

        $clienteId = $cliente->id;
        $cliente->deleteOrFail();

        $this->registrarAuditoria(
            'Eliminado',
            'ClienteTaurus',
            $cliente->numero_documento_ct,
            'Eliminacion de cliente t',
            ['evento' => 'Eliminacion de cliente taurus']
        );

        // ✅ Redirigir con la notificación de éxito
        return redirect()->route('aplicacion.dashboard', [
            'aplicacion' => $aplicacion,
            'rol' => ucfirst($nombreRol ?: 'SuperAdmin')
        ])->with('success', 'Cliente eliminado con éxito');
    }







}
