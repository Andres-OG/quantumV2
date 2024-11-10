<?php

namespace App\Http\Controllers;

use App\Models\Maestro;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MaestroController extends Controller
{
    public function index()
    {
        $maestros = Maestro::with('carrera')->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        
        return view('maestros.index', compact('maestros', 'name', 'nombre'));
    }

    public function create()
    {
        $carreras = Carrera::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.create', compact('carreras', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z][a-zA-Z\s]*$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre del maestro no debe estar completamente en mayúsculas.');
                    }
                }
            ],
            'no_cuenta' => [
                'required',
                'digits:7',
                'unique:maestros,no_cuenta'
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre del maestro no puede exceder los 255 caracteres.',
            'nombre.regex' => 'El nombre del maestro solo debe contener letras y espacios.',
            'no_cuenta.required' => 'El campo número de cuenta es obligatorio.',
            'no_cuenta.digits' => 'El número de cuenta debe tener exactamente 7 dígitos númericos.',
            'no_cuenta.unique' => 'El número de cuenta ya está registrado.',
            'carrera_id.required' => 'El campo carrera es obligatorio.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        Maestro::create($request->all());

        return redirect()->route('maestros.index')->with('success', 'Maestro registrado con éxito');
    }

    public function edit(Maestro $maestro)
    {
        $carreras = Carrera::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.edit', compact('maestro', 'carreras', 'name', 'nombre'));
    }

    public function update(Request $request, Maestro $maestro)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[a-zA-Z][a-zA-Z\s]*$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre del maestro no debe estar completamente en mayúsculas.');
                    }
                }
            ],
            'no_cuenta' => [
                'required',
                'digits:7',
                'unique:maestros,no_cuenta,' . $maestro->id
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ]);

        $maestro->update($request->all());

        return redirect()->route('maestros.index')->with('success', 'Maestro actualizado con éxito');
    }

    public function destroy(Maestro $maestro)
    {
        $maestro->delete();

        return redirect()->route('maestros.index')->with('success', 'Maestro eliminado con éxito');
    }
}
