<?php

namespace App\Exceptions;

use Throwable;
use Inertia\Inertia;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Auth\Access\AuthorizationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Support\Facades\Log;

class Handler extends ExceptionHandler
{
    public function render($request, Throwable $exception)
    {
        // Solo para peticiones de API, maneja por separado
        if ($request->is('api/*')) {
            return parent::render($request, $exception);
        }

        // Si la excepción es 404, renderiza la vista personalizada
        if ($exception instanceof NotFoundHttpException) {
            Log::error('Error 404: Página no encontrada', ['url' => $request->url()]);  // Registra el error
            return Inertia::render('Errors/Error404', ['status' => 404])
                ->toResponse($request)
                ->setStatusCode(404);
        }

        // Si es 403, renderiza la vista personalizada
        if ($exception instanceof AuthorizationException) {
            Log::error('Error 403: Acceso no autorizado', ['url' => $request->url()]);
            return Inertia::render('Errors/Error403', ['status' => 403])
                ->toResponse($request)
                ->setStatusCode(403);
        }

        // Si es error 500, renderiza la vista personalizada
        if ($exception instanceof HttpException && $exception->getStatusCode() === 500) {
            Log::error('Error 500: Error interno del servidor', ['url' => $request->url()]);
            return Inertia::render('Errors/Error500', ['status' => 500])
                ->toResponse($request)
                ->setStatusCode(500);
        }

        // Si la excepción no es ninguna de las anteriores, maneja el error normalmente
        return parent::render($request, $exception);
    }
}
