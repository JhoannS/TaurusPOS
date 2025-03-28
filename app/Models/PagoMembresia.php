<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PagoMembresia extends Model
{
    protected $table = 'pagos_membresia';

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';
    
    protected $fillable = [
        'id_cliente',
        'id_tienda',
        'id_medio_pago',
        'id_estado',
        'fecha_pago'
    ];

    public function cliente()
    {
        return $this->belongsTo(ClienteTaurus::class, 'id_cliente');
    }

    public function tienda()
    {
        return $this->belongsTo(TiendaSistematizada::class, 'id_tienda');
    }

    public function medioPago()
    {
        return $this->belongsTo(MedioPago::class, 'id_medio_pago');
    }

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'id_estado');
    }

}
