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
        $cliente = ClienteTaurus::with(['tienda.token', 'tienda.pagoMembresiaActual'])->findOrFail($id);
        $cliente->load('tienda.token'); // 👈 importante

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

        if ($request->filled('id_estado_token') && $cliente->tienda?->token) {
            $cliente->tienda->token->id_estado = $request->id_estado_token;
            $cliente->tienda->token->save();

            // Buscar el pago de membresía actual
            $pagoMembresia = $cliente->tienda?->pagoMembresiaActual;



            if ($pagoMembresia) {
                \Log::info('Entrando a actualización de fecha_activacion', ['antes' => $pagoMembresia->fecha_activacion]);
            
                $pagoMembresia->fecha_activacion = now();
                $pagoMembresia->save();
            
                \Log::info('Actualización hecha', ['después' => $pagoMembresia->fecha_activacion]);
            } else {
                \Log::warning('No se encontró pago activo para tienda ID: ' . $cliente->id_tienda);
            }
            

        }


        return redirect()->back()->with('success', 'Cliente y estado del token actualizados correctamente');
    }


    use AuthorizesRequests;

}
