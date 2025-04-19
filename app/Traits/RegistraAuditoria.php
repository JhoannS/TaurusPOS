<?php

namespace App\Traits;

use App\Models\Auditoria;
use App\Models\ClienteTaurus;


trait RegistraAuditoria
{
    public function registrarAuditoria($accion, $modelo = null, $modelo_id = null, $comentario = null, $cambios = null)
    {
        $userAgent = request()->header('User-Agent');
        $plataforma = null;

        if (stripos($userAgent, 'Windows') !== false) {
            $plataforma = 'Windows';
        } elseif (stripos($userAgent, 'Mac') !== false) {
            $plataforma = 'Mac';
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $plataforma = 'Linux';
        } elseif (stripos($userAgent, 'Android') !== false) {
            $plataforma = 'Android';
        } elseif (stripos($userAgent, 'iPhone') !== false) {
            $plataforma = 'iOS';
        }


        $user = ClienteTaurus::where('numero_documento_ct', auth()->id())->first();

        Auditoria::create([
            'user_id' => $user?->id,
            'modelo' => $modelo,
            'modelo_id' => $modelo_id,
            'accion' => $accion,
            'comentario' => $comentario,
            'ip' => request()->ip(),
            'url' => request()->fullUrl(),
            'navegador' => request()->header('User-Agent'),
            'plataforma' => $plataforma,
            'cambios' => is_array($cambios) ? json_encode($cambios) : $cambios,
            'created_at' => now(),
        ]);
    }
}
