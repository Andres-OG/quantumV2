<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Materia;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class MateriaController extends Controller
{
    public function index()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar las materias cuyas carreras pertenecen a la institución del usuario
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        return response()->json($materias);
    }

    public function vista()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar las materias cuyas carreras pertenecen a la institución
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.index', compact('materias', 'name', 'nombre'));
    }

    public function show(Materia $materia)
    {
        // Obtener una materia específica con su carrera
        return response()->json($materia->load('carrera'));
    }

    public function store(Request $request)
    {
        // Validación de los datos recibidos
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$/u', // Letras, números y espacios permitidos
            ],
            'carrera_id' => 'required|exists:carreras,id', // Debe ser una carrera válida
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la materia no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre de la materia solo puede contener letras, números y espacios.',
            'carrera_id.required' => 'Debe seleccionar una carrera.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput(); // Redirigir con los errores
        }

        // Crear la nueva materia en la base de datos
        Materia::create($request->all());

        // Redirigir con un mensaje de éxito
        return redirect()->route('materias.gestion')->with('success', 'Materia registrada con éxito.');
    }

    public function update(Request $request, Materia $materia)
    {
        // Validación de los datos recibidos
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'max:100',
                'regex:/^[\p{L}\d\s]+$/u',
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la materia no puede exceder los 100 caracteres.',
            'nombre.regex' => 'El nombre de la materia solo puede contener letras, números y espacios.',
            'carrera_id.required' => 'Debe seleccionar una carrera.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        // Si la validación falla
        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        // Actualizar la materia
        $materia->update($request->all());

        // Redirigir con un mensaje de éxito
        return redirect()->route('materias.gestion')->with('success', 'Materia actualizada con éxito.');
    }

    public function destroy(Materia $materia)
    {
        $materia->delete();

        return redirect()->route('materias.gestion')->with('success', 'Maestro eliminado con éxito');
    }

    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Obtener solo las carreras de la institución del usuario
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.gestion', compact('carreras', 'name', 'nombre'));
    }

    public function edit(Materia $materia)
    {
        $institutionId = Auth::user()->id_institution;

        // Verificar que la materia pertenece a una carrera de la institución
        if ($materia->carrera->created_by !== $institutionId) {
            abort(403, 'No tienes permiso para editar esta materia.');
        }

        // Obtener las carreras asociadas a la institución
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.gestion', compact('materia', 'carreras', 'nombre'));
    }

    public function gestionMaterias()
    {
        $institutionId = Auth::user()->id_institution;

        // Obtener las materias cuyas carreras pertenecen a la institución
        $materias = Materia::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        // Obtener solo las carreras de la institución
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('materias.gestion', compact('materias', 'carreras', 'name', 'nombre'));
    }
}
