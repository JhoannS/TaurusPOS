<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TiendaSistematizada extends Model
{
    protected $table = 'tiendas_sistematizadas';

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'id_estado',
        'id_token',
        'id_aplicacion_web',
        'logo_tienda',
        'nombre_tienda',
        'email_tienda',
        'telefono_tienda',
        'direccion_tienda',
        'barrio_tienda',
    ];

    protected $with = [
        'estado',
    ];

    // Relación con el estado
    public function estado()
    {
        return $this->belongsTo(Estados::class, 'id_estado', 'id');
    }
    // ✅ Relación con la aplicación
    public function aplicacion()
    {
        return $this->belongsTo(AplicacionWeb::class, 'id_aplicacion_web');
    }

    public function token()
    {
        return $this->belongsTo(TokenAcceso::class, 'id_token');
    }


    public function membresia()
    {
        return $this->belongsTo(Membresia::class, 'id_membresia');
    }

    public function pagosMembresias()
    {
        return $this->hasMany(PagoMembresia::class, 'id_tienda');

    }

    public function pagoMembresiaActual()
    {
        return $this->hasOne(PagoMembresia::class, 'id_tienda')
            ->latestOfMany(); // O usa un scope si quieres solo los activos
    }






}
