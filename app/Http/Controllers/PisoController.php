<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class PisoController extends Controller
{
    // Listar todos los pisos con sus edificios
    public function index()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar pisos cuyos edificios pertenecen a la institución
        $pisos = Piso::whereHas('edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('edificio')->get();

        return response()->json($pisos, 200);
    }

    // Mostrar formulario de creación de piso
    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar edificios de la institución
        $edificios = Edificio::where('id_institution', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('pisos.gestion', compact('edificios', 'name', 'nombre'));
    }

    public function vista()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar pisos cuyos edificios pertenecen a la institución
        $pisos = Piso::whereHas('edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('edificio')->get();

        $nombre = Session::get('institution_name', 'Institución');

        return view('pisos.index', compact('pisos', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => [
                'required',
                'regex:/^(PB|[1-9])$/',
            ],
            'idEdificio' => 'required|exists:edificios,id',
        ], [
            'numero.required' => 'El campo número es obligatorio.',
            'numero.regex' => 'El número solo puede ser "PB" (Planta Baja) o un número entre 1 y 9.',
            'idEdificio.required' => 'El campo edificio es obligatorio.',
            'idEdificio.exists' => 'El edificio seleccionado no es válido.',
        ]);

        $existePiso = Piso::where('numero', $request->numero)
            ->where('idEdificio', $request->idEdificio)
            ->exists();

        if ($existePiso) {
            return redirect()->back()->with('error', 'Ya existe un piso con ese número en este edificio.');
        }

        Piso::create($request->all());

        return redirect()->route('pisos.gestion')->with('success', 'Piso registrado correctamente.');
    }

    // Mostrar un piso específico
    public function show(Piso $piso)
    {
        return response()->json($piso, 200); // Retorna el piso en formato JSON
    }

    // Actualizar un piso existente
    public function update(Request $request, Piso $piso)
    {
        $request->validate([
            'numero' => [
                'required',
                'regex:/^(PB|[1-9])$/',
            ],
            'idEdificio' => 'required|exists:edificios,id',
        ], [
            'numero.required' => 'El campo número es obligatorio.',
            'numero.regex' => 'El número solo puede ser "PB" (Planta Baja) o un número entre 1 y 9.',
            'idEdificio.required' => 'El campo edificio es obligatorio.',
            'idEdificio.exists' => 'El edificio seleccionado no es válido.',
        ]);

        $piso->update([
            'numero' => $request->numero,
            'idEdificio' => $request->idEdificio,
        ]);

        return redirect()->route('pisos.gestion')->with('success', 'Piso actualizado correctamente');
    }

    // Eliminar un piso
    public function destroy(Piso $piso)
    {
        $piso->delete();

        return redirect()->route('pisos.gestion')->with('success', 'Piso eliminado correctamente.');
    }

    // Mostrar el formulario de edición de un piso
    public function edit(Piso $piso)
    {
        $institutionId = Auth::user()->id_institution;

        // Verificar que el piso pertenece a un edificio de la institución
        if ($piso->edificio->id_institution !== $institutionId) {
            abort(403, 'No tienes permiso para editar este piso.');
        }

        // Filtrar edificios de la institución
        $edificios = Edificio::where('id_institution', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('pisos.gestion', compact('piso', 'edificios', 'name', 'nombre'));
    }

    public function gestionPisos()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar pisos cuyos edificios pertenecen a la institución
        $pisos = Piso::whereHas('edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('edificio')->get();

        // Filtrar edificios de la institución
        $edificios = Edificio::where('id_institution', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('pisos.gestion', compact('pisos', 'edificios', 'name', 'nombre'));
    }

    public function getPisosPorEdificio($idEdificio)
    {
        // Obtener los pisos que pertenecen al edificio proporcionado
        $pisos = Piso::where('idEdificio', $idEdificio)->get(['id', 'numero']);

        // Devolver los pisos en formato JSON
        return response()->json($pisos);
    }
}
