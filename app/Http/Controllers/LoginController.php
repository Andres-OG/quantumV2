<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar encontrar al usuario
        $user = User::where('email', $request->email)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($request->password, $user->password)) {
            // Verificar si la institución está activa
            if ($user->institution && !$user->institution->status) {
                return redirect()->route('confirmation')->withErrors([
                    'institution' => 'La institución está inactiva. Por favor, espera a que se active.',
                ]);
            }

            // Autenticar al usuario
            Auth::login($user);

            // Redirección basada en rol
            switch ($user->id_role) {
                case 1: // Rol de administrador
                    return redirect()->route('dashboard');
                case 2: // Rol de usuario normal
                    return redirect()->route('main');
                default:
                    return redirect()->route('welcome')->with('status', 'Acceso denegado: rol no reconocido.');
            }
        }

        // Si la autenticación falla, redirige a la misma página con un mensaje de error
        return back()->withErrors(['email' => 'Credenciales incorrectas'])->withInput();
    }

    public function logout(Request $request)
    {
        // Cierra la sesión sin tokens (si no usas tokens)
        Auth::logout();

        return redirect()->route('welcome');
    }
}