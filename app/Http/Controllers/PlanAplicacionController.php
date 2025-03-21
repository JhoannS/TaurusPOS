<?php

namespace App\Http\Controllers;

use App\Models\PlanAplicacion;
use App\Models\DetallesPlan;

class PlanAplicacionController extends Controller
{
    public function mostrarDetalles()
    {
        $user = auth()->user();

        // Verifica que la tienda esté vinculada y tenga el ID de aplicación correcto
        if (!$user->tienda || $user->tienda->id_aplicacion_web != 19) {
            abort(404);
        }

        // Obtener el plan de aplicación con los detalles
        $detalles = PlanAplicacion::with('detalles')->where('id', $user->tienda->id_plan_aplicacion)->first();

        if (!$detalles) {
            abort(404, 'No se encontraron detalles del plan.');
        }

        // Transformar los datos al formato esperado por Vue
        $barsData = [
            [
                'id' => 'bar1',
                'title' => 'Sucursales activas:',
                'segments' => $detalles->detalles->cantidad_sucursales ? [
                    [
                        'max' => $detalles->detalles->cantidad_sucursales,
                        'value' => $detalles->detalles->cantidad_sucursales,
                        'color' => 'bg-blue-500',
                        'tag' => 'Sucursales activas'
                    ]
                ] : []
            ],
            [
                'id' => 'bar2',
                'title' => 'Gestión usuarios:',
                'segments' => [
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_empleados ?? 0,
                        'color' => 'bg-purple-500',
                        'tag' => 'Empleados'
                    ],
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_proveedores ?? 0,
                        'color' => 'bg-yellow-500',
                        'tag' => 'Proveedores'
                    ],
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_clientes ?? 0,
                        'color' => 'bg-orange-500',
                        'tag' => 'Clientes'
                    ]
                ]
            ],
            [
                'id' => 'bar3',
                'title' => 'Facturación POS',
                'segments' => [
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_facturacion_electronica ?? 0,
                        'color' => 'bg-teal-500',
                        'tag' => 'Electrónica'
                    ],
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_facturacion_fisica ?? 0,
                        'color' => 'bg-pink-500',
                        'tag' => 'Digital'
                    ]
                ]
            ],
            [
                'id' => 'bar4',
                'title' => 'Gestión de inventarios',
                'segments' => [
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_productos ?? 0,
                        'color' => 'bg-cyan-500',
                        'tag' => 'Productos'
                    ],
                    [
                        'max' => 9999,
                        'value' => $detalles->detalles->cantidad_servicios ?? 0,
                        'color' => 'bg-indigo-500',
                        'tag' => 'Servicios'
                    ]
                ]
            ],
            [
                'id' => 'bar5',
                'title' => 'Gestión de mesas:',
                'segments' => [
                    [
                        'max' => 200,
                        'value' => $detalles->detalles->cantidad_mesas ?? 0,
                        'color' => 'bg-emerald-500',
                        'tag' => 'Registradas'
                    ]
                ]
            ]
        ];

        // Pasar los datos a la vista de Inertia
        return inertia('Apps/Essentials/Administrador/Dashboard/Dashboard', [
            'barsData' => $barsData
        ]);
    }
}
