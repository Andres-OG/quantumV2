<?php

namespace App\Http\Controllers;

use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class MateriaController extends Controller
{
    public function index()
    {
        $materias = Materia::with('carrera')->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        
        return view('materias.index', compact('materias', 'name', 'nombre'));
    }

    public function create()
    {
        $carreras = Carrera::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.create', compact('carreras', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-Z\s]+$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre de la materia no debe estar completamente en mayúsculas.');
                    }
                }
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la materia no puede exceder los 20 caracteres.',
            'nombre.regex' => 'El nombre de la materia solo debe contener letras y espacios.',
            'carrera_id.required' => 'El campo carrera es obligatorio.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        Materia::create($request->all());

        return redirect()->route('materias.index')->with('success', 'Materia registrada con éxito');
    }

    public function edit(Materia $materia)
    {
        $carreras = Carrera::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.edit', compact('materia', 'carreras', 'name', 'nombre'));
    }

    public function update(Request $request, Materia $materia)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-Z\s]+$/',
                function ($attribute, $value, $fail) {
                    if ($value === strtoupper($value)) {
                        $fail('El nombre de la materia no debe estar completamente en mayúsculas.');
                    }
                }
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la materia no puede exceder los 20 caracteres.',
            'nombre.regex' => 'El nombre de la materia solo debe contener letras y espacios.',
            'carrera_id.required' => 'El campo carrera es obligatorio.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        $materia->update($request->all());

        return redirect()->route('materias.index')->with('success', 'Materia actualizada con éxito');
    }

    public function destroy($id)
    {
        $materia = Materia::findOrFail($id);
        $materia->delete();

        return redirect()->route('materias.index')->with('success', 'Materia eliminada con éxito');
    }
}
