<?php

namespace App\Http\Controllers;

use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class RegisterInstitutionController extends Controller
{
    // Muestra el formulario de registro de institución
    // Muestra el formulario de registro de institución
    public function showInstitutionForm()
    {
        // Verifica si ya existe una institución registrada en la sesión
        $step = session('step_completed');
        if ($step) {
            // Redirige al paso correspondiente
            if ($step === 'institution_registered') {
                return redirect()->route('payment')->with('message', 'Ya has registrado una institución. Procede al pago.');
            } elseif ($step === 'payment_completed') {
                return redirect()->route('registerAdmin')->with('message', 'El pago ya ha sido realizado. Procede al registro del administrador.');
            } elseif ($step === 'admin_registered') {
                return redirect()->route('confirmation')->with('message', 'El registro está completo. Revisa la confirmación.');
            }
        }

        // Si no hay pasos completados, muestra el formulario de registro
        return view('registerInstitution');
    }

    // Almacena una nueva institución
    public function storeInstitution(Request $request)
    {
        // Verifica si ya se ha completado el registro de la institución
    if (session('step_completed') === 'institution_registered') {
        return redirect()->route('payment')->with('message', 'Ya has registrado una institución. Procede al pago.');
    }
        $validator = Validator::make($request->all(), [
            'institucion' => [
                'required',
                'string',
                'regex:/^[\pL\s\-]+$/u', // Permite letras, espacios y guiones
                'min:2',
                'max:100',
                function ($attribute, $value, $fail) {
                    if (preg_match('/(.)\\1{3,}/', $value)) {
                        $fail('El nombre de la institución no puede contener caracteres repetidos en exceso.');
                    }
                },
            ],
            'ColorP' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/', // Color de fondo
            'ColorS' => 'nullable|regex:/^#[0-9A-Fa-f]{6}$/', // Color del texto
        ], [
            'institucion.regex' => 'El nombre de la institución solo puede contener letras, espacios y guiones.',
            'institucion.required' => 'El nombre de la institución es obligatorio.',
            'institucion.min' => 'El nombre de la institución debe tener al menos 2 caracteres.',
            'institucion.max' => 'El nombre de la institución no puede exceder los 100 caracteres.',
            'ColorP.regex' => 'El color de fondo debe estar en formato hexadecimal.',
            'ColorS.regex' => 'El color del texto debe estar en formato hexadecimal.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // Verificar si la institución ya existe
        $existingInstitution = Institution::where('name', $request->institucion)->first();
        if ($existingInstitution) {
            return redirect()->back()
                ->withErrors(['institucion' => 'La institución ya está registrada.'])
                ->withInput();
        }

        // Crear la nueva institución con los colores
        $institution = Institution::create([
            'name' => $request->institucion,
            'ColorP' => $request->ColorP ?? '#2d3748',
            'ColorS' => $request->ColorS ?? '#ffffff',
            'terms_accepted' => true,
        ]);

        // Guardar el nombre de la institución en la sesión
        session(['institution_name' => $institution->name]);
        session(['step_completed' => 'institution_registered', 'institution_name' => $institution->name]);
        return redirect()->route('payment');
    }


    // Cambiar el estado de una institución (activa/inactiva)
    public function updateStatus(Request $request, $id)
    {
        $institution = Institution::findOrFail($id);
        $institution->status = $request->input('status');
        $institution->save();

        return redirect()->back()->with('status', 'Estado actualizado exitosamente.');
    }

    // Eliminar una institución
    public function deleteInstitution($id)
    {
        $institution = Institution::findOrFail($id);
        $institution->delete();

        return redirect()->back()->with('status', 'Institución eliminada exitosamente.');
    }

    // Mostrar todas las instituciones junto con sus administradores
    public function index()
    {
        $institutions = Institution::with('admin')->get();
        return view('institutions.index', compact('institutions'));
    }
}
