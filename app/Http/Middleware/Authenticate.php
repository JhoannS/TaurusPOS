<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Support\Facades\Redirect;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            return route('login.auth');
        }
    }

    public function handle($request, \Closure $next, ...$guards)
    {
        if (!auth()->check()) {
            return Redirect::guest(route('login.auth'))->with('error', 'Debes iniciar sesión primero.');
        }

        return $next($request);
    }
}
