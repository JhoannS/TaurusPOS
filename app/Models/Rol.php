<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'roles_administrativos';

    protected $fillable = [
        'tipo_rol',
    ];
}
