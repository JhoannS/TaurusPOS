<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacionWeb extends Model
{
    protected $table = 'aplicaciones_web';

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'nombre_app',
    ];
}
