<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Hash;

class RegisterInstitutionController extends Controller
{
    public function showInstitutionForm()
    {
        return view('registerInstitution');
    }
    public function storeInstitution(Request $request)
    {
        // Cambiar 'institution' a 'institucion' en la validación
        $request->validate([
            'institucion' => 'required|string|max:255',
        ]);
    
        // Comprobación de existencia de la institución
        $existingInstitution = Institution::where('name', $request->institucion)->first();
    
        if ($existingInstitution) {
            // Regresa al formulario con un error si la institución ya existe
            return redirect()->route('register')->withErrors(['institucion' => 'La institución ya está registrada.'])->withInput();
        }
    
        // Crear nueva institución
        $institution = Institution::create([
            'name' => $request->institucion,
        ]);
    
        session(['institution_name' => $institution->name]);
    
        return redirect()->route('payment.show');
    }
    

    public function processPayment(Request $request)
    {
        $paymentSuccess = true;

        if ($paymentSuccess) {
            $institutionName = Session::get('institution_name');

            Institution::where('name', $institutionName)->update([
                'payment' => 7000.00,
            ]);

            Session::forget('institution_name');

            return redirect()->route('dashboard')->with('status', 'Pago realizado y registro completado.');
        } else {
            return redirect()->route('payment.show')->withErrors('Error en el pago. Inténtalo de nuevo.');
        }
    }

    public function showAdminRegistrationForm()
    {
        $instituciones = Institution::all();
        return view('register-admin', compact('institutions'));
    }
}
