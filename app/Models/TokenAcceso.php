<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenAcceso extends Model
{
    protected $table = 'token_accesos';

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'token_activacion',
        'id_estado',
        'id_tienda_sistematizada'
    ];

    public function estado()
    {
        return $this->belongsTo(Estados::class, 'id_estado');
    }

     // Relación con TiendaSistematizada (cada token pertenece a una tienda)
     public function tienda()
     {
         return $this->belongsTo(TiendaSistematizada::class, 'id_tienda_sistematizada');
     }
}
