<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use App\Models\Rol;
use App\Models\TiendaSistematizada;
use App\Models\TipoDocumento;
use App\Models\Estados;
use App\Models\Membresia;
use App\Models\AplicacionWeb;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use App\Traits\RegistraAuditoria; // 👈 Importa el trait correctamente aquí




class EditarClienteTaurusController extends Controller
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


    public function editar($aplicacion, $rol, $id)
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

        if (!in_array($user->rol->id, [1, 2, 3, 4])) {
            abort(403);
        }

        // Consulta del cliente por ID
        $cliente = ClienteTaurus::select(
            'clientes_taurus.id',
            DB::raw("CONCAT(clientes_taurus.nombres_ct, ' ', clientes_taurus.apellidos_ct) AS nombre_completo"),
            'clientes_taurus.nombres_ct',
            'clientes_taurus.apellidos_ct',
            'clientes_taurus.numero_documento_ct',
            'clientes_taurus.email_ct',
            'clientes_taurus.telefono_ct',
            'clientes_taurus.id',
            'clientes_taurus.id_rol',
            'clientes_taurus.id_estado',
            'clientes_taurus.id_tienda',
            'clientes_taurus.id_tipo_documento',
            'clientes_taurus.id_estado',

            'clientes_taurus.fecha_creacion',
            'clientes_taurus.fecha_modificacion',

            DB::raw('COALESCE(tiendas_sistematizadas.nombre_tienda, "Sin tienda") as nombre_tienda'),
            'token_accesos.token_activacion as token',
            'token_accesos.id_estado as id_estado_token',
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
            'estado_pago.tipo_estado as estado_pago',
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
        $roles = Rol::all();
        $tipoDocumentos = TipoDocumento::all();
        $aplicacionesAgrupadas = AplicacionWeb::with('membresia')
            ->get()
            ->groupBy('id_membresia')
            ->map(function ($grupo, $idMembresia) {
                return [
                    'id_membresia' => $idMembresia,
                    'nombre_membresia' => optional($grupo->first()->membresia)->nombre_membresia ?? 'Sin Membresía',
                    'apps' => $grupo->map(function ($app) {
                        return [
                            'id' => $app->id,
                            'nombre_app' => $app->nombre_app,
                        ];
                    })->values(),
                ];
            })
            ->values();




        return Inertia::render('Apps/' . ucfirst($aplicacion) . '/' . ucfirst($rol) . '/EditarClienteTaurus/EditarClienteTaurus', [
            'auth' => ['user' => $user],
            'cliente' => $cliente,
            'tiendas' => $tiendas,
            'membresias' => $membresias,
            'estados' => $estados,
            'aplicacionesAgrupadas' => $aplicacionesAgrupadas, // 👈 nuevo
            'roles' => $roles,
            'tiposDocumento' => $tipoDocumentos,
        ]);

    }

    public function actualizar(Request $request, $aplicacion, $rol, $id)
{
    $cliente = ClienteTaurus::with(['tienda.token', 'tienda.aplicacion.membresia', 'tienda.pagoMembresiaActual'])->findOrFail($id);
    $original = $cliente->toArray(); // Estado antes del cambio

    $validated = $request->validate([
        'nombres_ct' => 'required|string|max:100',
        'apellidos_ct' => 'required|string|max:100',
        'numero_documento_ct' => 'required|string|max:50|unique:clientes_taurus,numero_documento_ct,' . $cliente->id,
        'email_ct' => 'nullable|email|max:100',
        'telefono_ct' => 'nullable|string|max:20',
        'id_rol' => 'required|exists:roles_administrativos,id',
        'id_estado' => 'required|exists:estados,id',
        'id_tienda' => 'required|exists:tiendas_sistematizadas,id',
        'id_tipo_documento' => 'required|exists:tipo_documentos,id',
        'id_estado_token' => 'nullable|exists:estados,id',
    ]);

    // Actualizar cliente
    $cliente->fill($validated);
    $cliente->fecha_modificacion = now();
    $cliente->save();

    // Actualizar el token si viene estado
    if ($request->filled('id_estado_token') && $cliente->tienda?->token) {
        $cliente->tienda->token->id_estado = $request->id_estado_token;
        $cliente->tienda->token->save();

        // Si el token está en estado activo, actualizamos la fecha de activación y días restantes
        $estadoActivo = 1; // ID del estado "activo", cámbialo si tienes otro ID

        if ((int)$request->id_estado_token === $estadoActivo) {
            $pagoMembresia = $cliente->tienda->pagoMembresiaActual;
            $membresia = $cliente->tienda->aplicacion->membresia;

            if ($pagoMembresia && $membresia) {
                $pagoMembresia->fecha_activacion = now();
                $pagoMembresia->dias_restantes = $membresia->duracion ?? 0; // valor por defecto si no hay duracion
                $pagoMembresia->save();
            }
        }
    }

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

    $nombreAplicacion = $user->tienda->aplicacion->nombre_app ?? null;
    $rol = $user->rol->tipo_rol ?? null;

    $user = auth()->user();
        $cambios = [
            'antes' => collect($original)->only(array_keys($cliente->getChanges())),
            'despues' => $cliente->getChanges(),
            'editado_por' => $user->nombres_ct,
            'usuario_id' => $user->id,
        ];

        $this->registrarAuditoria(
            'Modificado',
            'ClienteTaurus',
            $cliente->id,
            'El usuario ' . $user->nombres_ct . ' actualizó los datos del cliente.',
            $cambios
        );

    return redirect()->route('aplicacion.editarClienteTaurus.id', [
        'aplicacion' => ucfirst($nombreAplicacion),
        'id' => ucfirst($id),
        'rol' => ucfirst($rol),
    ])->with('success', 'Usuario módificado con éxito.');
}


    use AuthorizesRequests;

}
