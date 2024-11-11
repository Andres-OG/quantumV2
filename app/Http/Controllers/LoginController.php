<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function login(Request $request)
{
    // Validar la solicitud
    $request->validate([
        'email' => 'required|email',
        'password' => 'required',
    ]);

    // Intentar autenticar al usuario
    if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        $user = Auth::user();

        // Lógica de redirección según el rol
        switch ($user->id_role) {
            case 1:
                return redirect()->route('dashboard');
            case 2:
                if ($user->status) {
                    return redirect()->route('main');
                } else {
                    return redirect()->route('confirmation');
                }
            default:
                return redirect()->route('welcome')->with('status', 'Acceso denegado.');
        }
    }

    // Si la autenticación falla
    return redirect()->back()->withErrors(['email' => 'Credenciales incorrectas.'])->withInput();
}

    public function logout()
    {
        Auth::logout();
        return redirect()->route('welcome');
    }

}
