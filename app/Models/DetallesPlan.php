<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetallesPlan extends Model
{
    protected $table = 'detalles_plan';

    protected $fillable = [
        'id_plan_aplicacion',
        'cantidad_sucursales',
        'cantidad_empleados',
        'cantidad_proveedores',
        'cantidad_facturacion_electronica',
        'cantidad_facturacion_fisica',
        'cantidad_productos',
        'cantidad_servicios',
        'cantidad_mesas',
        'manejo_reservas',
        'manejo_contabilidad',
        'manejo_pos',
        'manejo_control_gastos',
        'manejo_etiquetado_barras',
        'manejo_codigos_qr',
        'manejo_orden_trabajo',
        'manejo_encuestas',
        'fecha_creacion',
        'fecha_modificacion',
    ];
}
