<?php

// app/Models/Auditoria.php

namespace App\Models;
use App\Models\ClienteTaurus;
use Illuminate\Database\Eloquent\Model;

class Auditoria extends Model
{
    protected $fillable = [
        'user_id', 'accion', 'modelo', 'modelo_id', 'comentario', 'ip',
        'navegador', 'plataforma', 'url', 'cambios',
    ];

    protected $casts = [
        'cambios' => 'array',
    ];

    public static function crearRegistro($accion, $model, $old = null, $new = null)
    {
        $user = auth()->user();

        self::create([
            'user_id' => optional($user)->id,
            'accion' => $accion,
            'modelo' => class_basename($model),
            'modelo_id' => $model->id ?? null,
            'comentario' => "$accion sobre " . class_basename($model),
            'ip' => request()->ip(),
            'navegador' => request()->header('User-Agent'),
            'plataforma' => PHP_OS,
            'url' => request()->fullUrl(),
            'cambios' => $old && $new ? ['antes' => $old, 'despues' => $new] : null,
        ]);
    }

    public function user()
{
    return $this->belongsTo(ClienteTaurus::class, 'user_id');
}
}
