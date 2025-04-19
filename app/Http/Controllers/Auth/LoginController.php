<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\ClienteTaurus;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Hash;
use App\Traits\RegistraAuditoria; // 👈 Importa el trait correctamente aquí

class LoginController extends Controller
{
    use RegistraAuditoria; // 👈 Usa el trait aquí a nivel de clase

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

        $cliente = ClienteTaurus::with('rol', 'tienda.aplicacion', 'tienda.token', 'tienda.membresia.pagos_membresia')
            ->where('numero_documento_ct', $request->numero_documento_ct)
            ->first();

        if (!$cliente) {
            return back()->withErrors([
                'numero_documento_ct' => 'No reconocemos ese usuario :(',
            ]);
        }

        if (!Hash::check($request->contrasenia_ct, $cliente->contrasenia_ct)) {
            return back()->withErrors([
                'contrasenia_ct' => 'Credenciales incorrectas, intenta de nuevo.',
            ]);
        }

        $ultimoPago = $cliente->pagosMembresias->last();
        if ($ultimoPago && $ultimoPago->id_estado === 9) {
            Auth::logout();
            return back()->withErrors([
                'numero_documento_ct' => 'Ponte al día con tu pago para seguir disfrutando de la app.',
            ]);
        }

        Auth::login($cliente);

        // ✅ Registrar el login
        $this->registrarAuditoria(
            'Iniciado sesión',
            'ClienteTaurus',
            $cliente->numero_documento_ct,
            'Ingreso al sistema',
            ['evento' => 'inicio de sesion']
        );

        if (
            !$cliente->tienda ||
            !$cliente->tienda->token ||
            !$cliente->tienda->token->token_activacion ||
            $cliente->tienda->token->id_estado === 2
        ) {
            Auth::logout();
            return back()->withErrors([
                'numero_documento_ct' => 'Token no válido o inactivo, contáctanos.',
            ]);
        }

        $nombreAplicacion = $cliente->tienda->aplicacion->nombre_app ?? null;
        $rol = $cliente->rol->tipo_rol ?? null;

        if (!$nombreAplicacion || !$rol) {
            Auth::logout();
            return back()->withErrors([
                'numero_documento_ct' => 'Error al obtener aplicación o rol.',
            ]);
        }

        return redirect()->route('aplicacion.dashboard', [
            'aplicacion' => ucfirst($nombreAplicacion),
            'rol' => ucfirst($rol),
        ])->with('success', 'Bienvenido por aquí');
    }

    // ✅ Cerrar sesión
    public function logout(Request $request)
    {
        $clienteId = auth()->id(); // Guardamos el ID antes de cerrar sesión
    
        $this->registrarAuditoria(
            'Cerrado sesión',
            'ClienteTaurus',
            $clienteId,
            'Cierre de sesion',
            ['evento' => 'cierre de sesion']
        );
    
        Auth::logout();
    
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    
        return redirect()->route('login.auth')->with('success', 'Nos vemos luego');
    }
    
}
