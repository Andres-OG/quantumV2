<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;

class MainController extends Controller
{
    public function showMain()
    {
        $institutionName = Session::get('institution_name');
        $institucion = Institution::where('name', $institutionName)->first();
        
        $usuarioName = Session::get('usuario_name');
        $usuario = User::where('name', $usuarioName)->first();

        if ($institucion) {
            $nameIn = $institucion->name;
            $usuarioV = $usuario ? $usuario->name : 'Usuario no encontrado';
            
            return view('main', [
                'nameI' => $nameIn,
                'name' => $usuarioV
            ]);
        } else {
            return redirect()->route('login')->withErrors('Institución no encontrada o usuario no autenticado.');
        }
    }
}
