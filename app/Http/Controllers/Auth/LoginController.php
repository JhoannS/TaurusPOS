<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Hash;

class LoginController extends Controller
{
    // ✅ Mostrar formulario de login
    public function show()
    {
        return Inertia::render('Auth/Auth');
    }

    // ✅ Procesar el login
    public function login(Request $request)
{
    $request->validate([
        'numero_documento_ct' => 'required|string',
        'contrasenia_ct' => 'required|string',
    ], [
        'numero_documento_ct.required' => 'El usuario es requerido.',
        'contrasenia_ct.required' => 'La contraseña es requerida.',
    ]);

    // ✅ Verifica si el usuario existe
    $cliente = ClienteTaurus::with('rol', 'tienda.aplicacion', 'tienda.token')
        ->where('numero_documento_ct', $request->numero_documento_ct)
        ->first();

    if (!$cliente) {
        return back()->withErrors([
            'numero_documento_ct' => 'No reconocemos ese usuario :(',
        ]);
    }

    // ✅ Intenta autenticar usando 'numero_documento_ct' y 'contrasenia_ct'
    if (!Hash::check($request->contrasenia_ct, $cliente->contrasenia_ct)) {
        return back()->withErrors([
            'contrasenia_ct' => 'Credenciales incorrectas, intenta de nuevo.',
        ]);
    }

    Auth::login($cliente);

    // ✅ Validación de token
    if (
        !$cliente->tienda ||
        !$cliente->tienda->token ||
        !$cliente->tienda->token->token_activacion
    ) {
        Auth::logout();
        return back()->withErrors([
            'numero_documento_ct' => 'Token no válido o inactivo, contactanos.',
        ]);
    }

    // ✅ Obtener nombre de la aplicación y rol
    $nombreAplicacion = $cliente->tienda->aplicacion->nombre_app ?? null;
    $rol = $cliente->rol->tipo_rol ?? null;

    if (!$nombreAplicacion || !$rol) {
        Auth::logout();
        return back()->withErrors([
            'numero_documento_ct' => 'Error al obtener aplicación o rol.',
        ]);
    }

    // ✅ Redirige dinámicamente
    return redirect()->route('aplicacion.dashboard', [
        'aplicacion' => ucfirst($nombreAplicacion),
        'rol' => ucfirst($rol),
    ]);
}



    // ✅ Cerrar sesión
    public function logout()
    {
        Auth::logout();
        return redirect()->route('login.auth');
    }
}
