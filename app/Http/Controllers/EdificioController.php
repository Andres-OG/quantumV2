<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class EdificioController extends Controller
{
    // Mostrar solo los edificios de la institución del usuario
    public function index()
    {
        $institutionId = Auth::user()->id_institution;
        $edificios = Edificio::where('id_institution', $institutionId)->get();
        return response()->json($edificios);
    }

    // Mostrar solo los edificios de la institución del usuario en la vista
    public function vista()
    {
        $institutionId = Auth::user()->id_institution;
        $edificios = Edificio::where('id_institution', $institutionId)->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('edificios.index', compact('edificios', 'nombre'));
    }

    // Crear un nuevo edificio y asignarle la institución
    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:15',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/',
                'unique:edificios,nombre',
            ],
            'id_institution' => 'required|exists:institutions,id_institution', // Verifica que la institución sea válida
        ], [
            'nombre.required' => 'El nombre del edificio es obligatorio.',
            'nombre.max' => 'El nombre del edificio no puede exceder los 15 caracteres.',
            'nombre.regex' => 'El nombre del edificio solo debe contener letras y espacios.',
            'nombre.unique' => 'Ya existe un edificio con ese nombre.',
            'id_institution.required' => 'La institución es obligatoria.',
            'id_institution.exists' => 'La institución seleccionada no es válida.',
        ]);

        // Crear el edificio con la institución asociada
        Edificio::create([
            'nombre' => $request->nombre,
            'id_institution' => $request->id_institution,
        ]);

        return redirect()->route('edificios.gestion')->with('success', 'Edificio registrado correctamente.');
    }


    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('edificios.create', compact('name', 'nombre'));
    }
    // public function show($id)
    // {
    //     try {
    //         $edificio = Edificio::findOrFail($id);
    //         return response()->json($edificio, 200);

    //     } catch (ModelNotFoundException $e) {
    //         return response()->json([
    //             'message' => 'Edificio no encontrado'
    //         ], 404);

    //     } catch (\Exception $e) {
    //         return response()->json([
    //             'message' => 'Error al mostrar el edificio',
    //             'error' => $e->getMessage()
    //         ], 500);
    //     }
    // }

    public function update(Request $request, Edificio $edificio)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:15',
                'min:1',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ\s]+$/',
                function ($attribute, $value, $fail) use ($edificio) {
                    $existingEdificio = Edificio::where('nombre', $value)
                        ->where('id', '!=', $edificio->id)
                        ->first();

                    if ($existingEdificio) {
                        $fail('Ya existe un edificio con ese nombre.');
                    }
                },
            ],
        ]);

        $edificio->update(['nombre' => $request->nombre]);

        return redirect()->route('edificios.gestion')->with('success', 'Edificio actualizado correctamente.');
    }

    public function destroy(Edificio $edificio)
    {
        $institutionId = Auth::user()->id_institution;

        if ($edificio->id_institution !== $institutionId) {
            abort(403, 'No tienes permiso para eliminar este edificio.');
        }

        $edificio->delete();
        return redirect()->route('edificios.gestion')->with('success', 'Edificio eliminado correctamente.');
    }

    public function edit(Edificio $edificio)
    {
        $institutionId = Auth::user()->id_institution;

        if ($edificio->id_institution !== $institutionId) {
            abort(403, 'No tienes permiso para editar este edificio.');
        }

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        return view('edificios.gestion', compact('edificio', 'name', 'nombre'));
    }


    public function gestionEdificios()
    {
        $institutionId = Auth::user()->id_institution;
        $edificios = Edificio::where('id_institution', $institutionId)->get();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('edificios.gestion', compact('edificios', 'name', 'nombre'));
    }
}
