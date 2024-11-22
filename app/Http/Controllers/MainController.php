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
        $user = Auth::user();

        if ($user) {
            $institution = $user->institution;

            if (!$institution) {
                return redirect()->route('login')->withErrors('Institución no encontrada o usuario no autenticado.');
            }

            return view('main.layoutMain', [
                'nameI' => $institution->name,
                'name' => $user->name,
            ]);
        } else {
            return redirect()->route('login')->withErrors('Usuario no autenticado.');
        }
    }
}
