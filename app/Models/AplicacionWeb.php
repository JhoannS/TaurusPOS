<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AplicacionWeb extends Model
{
    protected $table = 'aplicaciones_web';

    public const CREATED_AT = 'fecha_creacion';
    public const UPDATED_AT = 'fecha_modificacion';

    protected $fillable = [
        'id_estado',
        'id_plan_aplicacion',
        'id_membresia',
        'nombre_app',
    ];

   // ✅ Relación con el plan de aplicación
   public function plan()
{
    return $this->belongsTo(PlanAplicacion::class, 'id_plan_aplicacion')->with('detalles');
}

   // ✅ Relación con la membresía
   public function membresia()
   {
       return $this->belongsTo(Membresia::class, 'id_membresia');
   }

   // ✅ Relación con el estado
   public function estado()
   {
       return $this->belongsTo(Estados::class, 'id_estado');
   }

}
