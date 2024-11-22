<?php

namespace App\Http\Controllers;

use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class GrupoController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar grupos cuyas materias pertenecen a carreras de la institución
        $grupos = Grupo::whereHas('materia.carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('materia')->get();

        return response()->json($grupos);
    }

    public function vista()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar grupos cuyas materias pertenecen a carreras de la institución
        $grupos = Grupo::whereHas('materia.carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('materia')->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.index', compact('grupos', 'nombre'));
    }

    // Mostrar un grupo específico
    // public function show($id)
    // {
    //     // Buscar el grupo por ID con la materia asociada
    //     $grupo = Grupo::with('materia')->findOrFail($id);

    //     // Retornar el grupo en formato JSON
    //     return response()->json($grupo, 200);
    // }

    // Mostrar la vista de creación de un nuevo grupo
    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar materias relacionadas con carreras de la institución
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.gestion', compact('materias', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$/',
                'unique:grupos,nombre',
            ],
            'idMateria' => 'required|exists:materias,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.min' => 'El nombre del grupo debe tener al menos 2 caracteres.',
            'nombre.max' => 'El nombre del grupo no puede exceder los 50 caracteres.',
            'nombre.regex' => 'El nombre del grupo debe contener letras y números, como "S9" o "A1".',
            'nombre.unique' => 'Ya existe un grupo con ese nombre.',
            'idMateria.required' => 'El campo materia es obligatorio.',
            'idMateria.exists' => 'La materia seleccionada no es válida.',
        ]);

        $grupo = Grupo::create($request->all());

        return request()->wantsJson()
            ? response()->json(['message' => 'Grupo registrado con éxito', 'grupo' => $grupo], 201)
            : redirect()->route('grupos.gestion')->with('success', 'Grupo registrado con éxito');
    }

    public function edit(Grupo $grupo)
    {
        $institutionId = Auth::user()->id_institution;

        // Verificar que el grupo pertenece a una materia de la institución
        if ($grupo->materia->carrera->created_by !== $institutionId) {
            abort(403, 'No tienes permiso para editar este grupo.');
        }

        // Filtrar materias relacionadas con carreras de la institución
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.gestion', compact('grupo', 'materias', 'name', 'nombre'));
    }

    public function update(Request $request, Grupo $grupo)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^(?=.*[A-Za-z])(?=.*[0-9])[A-Za-z0-9]+$/',
            ],
            'idMateria' => 'required|exists:materias,id',
        ]);

        $grupo->update($request->all());

        return request()->wantsJson()
            ? response()->json(['message' => 'Grupo actualizado con éxito', 'grupo' => $grupo], 200)
            : redirect()->route('grupos.gestion')->with('success', 'Grupo actualizado con éxito');
    }

    public function destroy(Grupo $grupo)
    {
        $grupo->delete();

        return request()->wantsJson()
            ? response()->json(['message' => 'Grupo eliminado con éxito'], 200)
            : redirect()->route('grupos.gestion')->with('success', 'Grupo eliminado con éxito');
    }

    public function gestionGrupos()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar grupos cuyas materias pertenecen a carreras de la institución
        $grupos = Grupo::whereHas('materia.carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('materia')->get();

        // Filtrar materias relacionadas con carreras de la institución
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('grupos.gestion', compact('grupos', 'materias', 'name', 'nombre'));
    }
}
