<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;

class ConfirmationController extends Controller
{
    public function show()
    {
        $step = session('step_completed');

        if ($step !== 'admin_registered') {
            return redirect()->route('register')->with('error', 'Por favor, completa todos los pasos antes de acceder a la confirmación.');
        }

        $institutionName = Session::get('institution_name');

        if (!$institutionName) {
            return redirect()->route('login')->with('error', 'Por favor, inicia sesión nuevamente.');
        }

        $institution = Institution::where('name', $institutionName)->first();

        if ($institution) {
            return view('confirmation', ['institution_name' => $institution->name]);
        }

        return redirect()->route('home')->with('error', 'Institución no encontrada.');
    }
}
