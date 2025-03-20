<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that is loaded on the first page visit.
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determine the current asset version.
     */
    public function version(Request $request): ?string
{
    $manifestPath = public_path('build/manifest.json');

    if (file_exists($manifestPath)) {
        return md5_file($manifestPath);
    }

    return null;
}


    
    /**
     * Define the props that are shared by default.
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
{
    return [
        ...parent::share($request),

        'auth' => [
            'user' => $request->user()
                ? $request->user()->load('tienda', 'rol') // Carga relaciones
                : null,
        ],

        // Añade el nombre de la ruta actual
        'currentRoute' => fn () => $request->route()?->getName(),
    ];
}

}
