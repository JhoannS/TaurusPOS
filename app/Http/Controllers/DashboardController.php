<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\DB; // ✅ Importa la clase DB correctamente

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
        if (!Gate::allows('access-role', 4) || $user->rol->id != 4) {
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
                'membresias.precio as precio',
                \DB::raw('COALESCE(estados.tipo_estado, "Sin estado") as estado_tipo'),
                \DB::raw('COALESCE(token_estado.tipo_estado, "Sin estado") as estado_token'), // Estado del token
                'clientes_taurus.fecha_creacion'
            )
                ->leftJoin('tiendas_sistematizadas', 'clientes_taurus.id_tienda', '=', 'tiendas_sistematizadas.id')
                ->leftJoin('token_accesos', 'tiendas_sistematizadas.id_token', '=', 'token_accesos.id')
                ->leftJoin('aplicaciones_web', 'tiendas_sistematizadas.id_aplicacion_web', '=', 'aplicaciones_web.id')
                ->leftJoin('membresias', 'aplicaciones_web.id_membresia', '=', 'membresias.id')
                ->leftJoin('estados', 'clientes_taurus.id_estado', '=', 'estados.id')
                ->leftJoin('estados as token_estado', 'token_accesos.id_estado', '=', 'token_estado.id') // Join para estado del token
                ->orderBy('clientes_taurus.fecha_creacion', 'DESC')
                ->get();



                return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/Dashboard/Dashboard', [
                    'auth' => ['user' => $user],
                    'clientes' => $clientes,
                    'aplicacion' => $aplicacion, // ✅ PASA EL VALOR AQUÍ
                    'rol' => $rol, // ✅ PASA TAMBIÉN EL ROL
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
        try {
            $clientes = DB::table('clientes_taurus')
                ->join('token_accesos', 'clientes_taurus.id', '=', 'token_accesos.cliente_id')
                ->join('tiendas_sistematizadas', 'clientes_taurus.tienda_id', '=', 'tiendas_sistematizadas.id')
                ->where('token_accesos.token_activacion', 'TokenPrueba131121220125')
                ->select(
                    'clientes_taurus.nombres_ct',
                    'tiendas_sistematizadas.nombre_tienda'
                )
                ->get();

            return response()->json($clientes);
        } catch (\Exception $e) {
            // Devuelve el mensaje de error completo para depurar
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
{
    $cliente = ClienteTaurus::find($id);

    if (!$cliente) {
        if ($request->inertia()) {
            abort(404, 'Cliente no encontrado'); // ✅ Si es una solicitud Inertia, devuelve un error 404 de Inertia
        }

        return response()->json(['error' => 'Cliente no encontrado'], 404); // ✅ Si no es de Inertia, devuelve JSON
    }

    $this->authorize('delete', $cliente); // Usa la política para validar permisos

    $cliente->deleteOrFail();

    return response()->json([
        'message' => 'Cliente eliminado correctamente',
        'cliente_id' => $id
    ]);
}


}
