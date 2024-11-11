<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class CarreraController extends Controller
{
    public function index()
    {
        $carreras = Carrera::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.index', compact('carreras', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.create', compact('name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:30',
                'regex:/^[a-zA-Z][a-zA-Z\s]*$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre de la carrera no debe estar completamente en mayúsculas.');
                    }
                }
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la carrera no puede exceder los 30 caracteres.',
            'nombre.regex' => 'El nombre de la carrera solo debe contener letras y espacios.',
        ]);

        Carrera::create(['nombre' => $request->nombre]);

        return redirect()->route('carreras.index')->with('success', 'Carrera registrada con éxito');
    }

    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.edit', compact('carrera', 'name', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:30',
                'regex:/^[a-zA-Z][a-zA-Z\s]*$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre de la carrera no debe estar completamente en mayúsculas.');
                    }
                }
            ],
        ]);

        $carrera = Carrera::findOrFail($id);
        $carrera->nombre = $request->nombre;
        $carrera->save();

        return redirect()->route('carreras.index')->with('success', 'Carrera actualizada con éxito');
    }
}
