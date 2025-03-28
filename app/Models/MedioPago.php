<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedioPago extends Model
{
    protected $table = 'medios_pago';

    
    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = ['tipo_pago'];

    // ✅ Relación inversa con PagoMembresia
    public function pagos()
    {
        return $this->hasMany(PagoMembresia::class, 'id_medio_pago');
    }
}
