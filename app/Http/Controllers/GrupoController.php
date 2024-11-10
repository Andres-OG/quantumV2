<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class GrupoController extends Controller
{
    public function index()
    {
        $grupos = Grupo::with('materia')->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        
        return view('grupos.index', compact('grupos', 'name', 'nombre'));
    }

    public function create()
    {
        $materias = Materia::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.create', compact('materias', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:3',
                'regex:/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$/',
            ],
            'idMateria' => 'required|exists:materias,id',
            'periodo' => [
                'required',
                'string',
                'regex:/^\d{4}[AB]$/',
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.min' => 'El nombre del grupo debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre del grupo no puede exceder los 3 caracteres.',
            'nombre.regex' => 'El nombre del grupo debe contener letras y números, sin espacios, como "S9" o "A1".',
            'idMateria.required' => 'El campo materia es obligatorio.',
            'idMateria.exists' => 'La materia seleccionada no es válida.',
            'periodo.required' => 'El campo periodo es obligatorio.',
            'periodo.regex' => 'El periodo debe estar en formato de 4 dígitos seguidos de "A" o "B" (ej. 2024A o 2024B).',
        ]);

        Grupo::create($request->all());
        return redirect()->route('grupos.index')->with('success', 'Grupo registrado con éxito');
    }

    public function edit($id)
    {
        $grupo = Grupo::findOrFail($id);
        $materias = Materia::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.edit', compact('grupo', 'materias', 'name', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:3',
                'regex:/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$/',
            ],
            'idMateria' => 'required|exists:materias,id',
            'periodo' => [
                'required',
                'string',
                'regex:/^\d{4}[AB]$/',
            ],
        ]);

        $grupo = Grupo::findOrFail($id);
        $grupo->update($request->all());

        return redirect()->route('grupos.index')->with('success', 'Grupo actualizado con éxito');
    }

    public function destroy($id)
    {
        $grupo = Grupo::findOrFail($id);
        $grupo->delete();

        return redirect()->route('grupos.index')->with('success', 'Grupo eliminado con éxito');
    }
}
