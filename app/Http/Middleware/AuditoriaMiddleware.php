<?php

// app/Http/Middleware/AuditoriaMiddleware.php

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Models\Auditoria;

class AuditoriaMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            Auditoria::create([
                'user_id' => Auth::id(),
                'accion' => 'navegacion',
                'modelo' => null,
                'modelo_id' => null,
                'comentario' => 'Navegación a ' . $request->path(),
                'ip' => $request->ip(),
                'navegador' => $request->header('User-Agent'),
                'plataforma' => PHP_OS,
                'url' => $request->fullUrl(),
            ]);
        }

        return $next($request);
    }
}
