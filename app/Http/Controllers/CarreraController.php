<?php

namespace App\Http\Controllers;

use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Session;

class CarreraController extends Controller
{
    // Mostrar solo las carreras de la institución del usuario
    public function index()
    {
        $institutionId = Auth::user()->id_institution;
        $carreras = Carrera::where('created_by', $institutionId)->get();

        return response()->json([
            'carreras' => $carreras,
            'name' => Session::get('usuario_name', 'Usuario'),
            'institution_name' => Session::get('institution_name', 'Institución')
        ]);
    }

    // Mostrar solo las carreras de la institución del usuario en la vista
    public function vista()
    {
        $institutionId = Auth::user()->id_institution;
        $carreras = Carrera::where('created_by', $institutionId)->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.index', compact('carreras', 'name', 'nombre'));
    }

    // Crear una nueva carrera y asignarle la institución
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\p{L}\s\-\'\.,]+$/u',
                'unique:carreras,nombre'
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la carrera no puede exceder los 30 caracteres.',
            'nombre.regex' => 'El nombre de la carrera solo puede contener letras, espacios y caracteres válidos.',
            'nombre.unique' => 'Ya existe una carrera con ese nombre.',
        ]);

        // Asociar automáticamente la institución del usuario
        $institutionId = Auth::user()->id_institution;

        Carrera::create([
            'nombre' => $request->nombre,
            'created_by' => $institutionId,
        ]);

        return redirect()->route('carreras.gestion')->with('success', 'Carrera creada exitosamente.');
    }


    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.gestion', compact('name', 'nombre'));
    }

    // Editar una carrera existente
    public function edit($id)
    {
        $carrera = Carrera::findOrFail($id);

        // Validar que la carrera pertenece a la institución del usuario
        if ($carrera->created_by !== Auth::user()->id_institution) {
            abort(403, 'No tienes permiso para editar esta carrera.');
        }

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.gestion', compact('carrera', 'name', 'nombre'));
    }

    // Actualizar una carrera existente
    public function update(Request $request, $id)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:30',
                'regex:/^[\p{L}\s\-\'\.,]+$/u', // Acepta letras, espacios, guiones, comas, puntos y apóstrofes
                'unique:carreras,nombre,' . $id // Validar que el nombre sea único excepto para la carrera actual
            ],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre de la carrera no puede exceder los 30 caracteres.',
            'nombre.regex' => 'El nombre de la carrera solo puede contener letras, espacios y caracteres válidos.',
            'nombre.unique' => 'Ya existe una carrera con ese nombre.' // Mensaje si ya existe
        ]);

        // Buscar la carrera por su ID
        $carrera = Carrera::findOrFail($id);

        // Actualizar el nombre de la carrera
        $carrera->nombre = $request->nombre;

        // Guardar la carrera actualizada
        $carrera->save();

        // Almacenar un mensaje flash en la sesión
        session()->flash('success', 'Carrera actualizada con éxito');

        // Redirigir a la vista de listado de carreras
        return redirect()->route('carreras.gestion');
    }

    public function gestionCarreras()
    {
        $institutionId = Auth::user()->id_institution;
        $carreras = Carrera::where('created_by', $institutionId)->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('carreras.gestion', compact('carreras', 'name', 'nombre'));
    }
}
