<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanAplicacion extends Model
{
    protected $table = 'planes_aplicaciones';

    public function detalles()
    {
        return $this->hasOne(DetallesPlan::class, 'id_plan_aplicacion');
    }
}
