<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membresia extends Model
{
    protected $table = 'membresias';

    protected $fillable = [
        'id_aplicacion_web',
        'id_estado',
        'nombre_membresia',
        'precio',
        'periodo',
        'duracion',
        'descripcion'
    ];

    // ✅ Relación con estado
    public function estado()
    {
        return $this->belongsTo(Estados::class, 'id_estado');
    }

    // ✅ Relación con aplicación web
    public function aplicacion()
    {
        return $this->belongsTo(AplicacionWeb::class, 'id_aplicacion_web');
    }
}
