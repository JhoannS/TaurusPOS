<?php

namespace App\Http\Controllers;

use App\Models\ClienteTaurus;
use App\Models\Producto;
use App\Models\Servicio;
use App\Models\Empleado;
use App\Models\Proveedor;
use App\Models\Factura;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class PlanAplicacionController extends Controller
{
    public function index()
    {
        $clienteId = Auth::id();

        $detalles = ClienteTaurus::with('detallesPlan')->where('id', $clienteId)->first();

        // Contamos los registros reales desde la base de datos
        $data = [
            'cantidad_sucursales' => $detalles->detallesPlan->cantidad_sucursales ?? 0,
            'cantidad_empleados' => Empleado::where('cliente_id', $clienteId)->count(),
            'cantidad_proveedores' => Proveedor::where('cliente_id', $clienteId)->count(),
            'cantidad_productos' => Producto::where('cliente_id', $clienteId)->count(),
            'cantidad_servicios' => Servicio::where('cliente_id', $clienteId)->count(),
            'cantidad_facturacion_electronica' => Factura::where('cliente_id', $clienteId)->where('tipo', 'electronica')->count(),
            'cantidad_facturacion_fisica' => Factura::where('cliente_id', $clienteId)->where('tipo', 'fisica')->count(),
            'manejo_reservas' => $detalles->detallesPlan->manejo_reservas ?? 'No',
            'manejo_pos' => $detalles->detallesPlan->manejo_pos ?? 'No',
            'manejo_control_gastos' => $detalles->detallesPlan->manejo_control_gastos ?? 'No'
        ];

        return Inertia::render('Dashboard', [
            'detalles' => $data
        ]);
    }
}
