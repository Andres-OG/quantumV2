<?php

// ConfirmationController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;


class ConfirmationController extends Controller
{
    public function show()
{
    $institutionName = Session::get('institution_name');
    
    if (!$institutionName) {
        return redirect()->route('login')->withErrors('Por favor inicia sesión nuevamente.');
    }

    $institucion = Institution::where('name', $institutionName)->first();
    if ($institucion) {
        $nameIn = $institucion->name;
        
        return view('confirmation', [
            'nameI' => $nameIn
        ]);
    } else {
        return redirect()->route('login')->withErrors('Institución no encontrada o usuario no autenticado.');
    }
}

}