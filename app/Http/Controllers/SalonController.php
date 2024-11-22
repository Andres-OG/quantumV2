<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Salon;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class SalonController extends Controller
{
    public function index(): JsonResponse
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar salones cuyos pisos pertenecen a edificios de la institución
        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('piso', 'piso.edificio')->get();

        return response()->json($salones);
    }

    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar edificios y pisos de la institución
        $edificios = Edificio::where('id_institution', $institutionId)->get();
        $pisos = Piso::whereHas('edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('salones.gestion', compact('edificios', 'pisos', 'name', 'nombre'));
    }


    // Método index en el controlador
    public function vista()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar salones cuyos pisos pertenecen a edificios de la institución
        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('piso', 'piso.edificio')->get();

        $nombre = Session::get('institution_name', 'Institución');

        return view('salones.index', compact('salones', 'nombre'));
    }

    public function edit(Salon $salon)
    {
        $institutionId = Auth::user()->id_institution;

        // Verificar que el salón pertenece a un piso de un edificio de la institución
        if ($salon->piso->edificio->id_institution !== $institutionId) {
            abort(403, 'No tienes permiso para editar este salón.');
        }

        // Filtrar edificios y pisos de la institución
        $edificios = Edificio::where('id_institution', $institutionId)->get();
        $pisos = Piso::where('idEdificio', $salon->piso->idEdificio)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('salones.gestion', compact('salon', 'edificios', 'pisos', 'name', 'nombre'));
    }

    public function show($id): JsonResponse
    {
        $salon = Salon::with('piso', 'piso.edificio')->findOrFail($id);
        return response()->json($salon);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$/',
                Rule::unique('salones')->where(function ($query) use ($request) {
                    return $query->where('idPiso', $request->idPiso);
                }),
            ],
            'idEdificio' => 'required|integer',
            'idPiso' => 'required|integer',
        ]);

        if ($request->idSalon) {
            // Actualización
            $salon = Salon::findOrFail($request->idSalon); // Usa la clave primaria correcta
            $salon->update($data);
            return redirect()->route('salones.gestion')->with('success', 'Salón actualizado correctamente');
        } else {
            // Creación
            Salon::create($data);
            return redirect()->route('salones.gestion')->with('success', 'Salón creado correctamente');
        }
    }
    public function update(Request $request, Salon $salon)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$/'
            ],
            'idPiso' => 'required|integer|exists:pisos,id',
        ], [
            'nombre.required' => 'El nombre es obligatorio.',
            'nombre.string' => 'El nombre debe ser un texto válido.',
            'nombre.max' => 'El nombre no debe exceder los 20 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras, números, acentos y espacios.',
            'idPiso.required' => 'El piso es obligatorio.',
            'idPiso.exists' => 'El piso seleccionado no es válido.',
        ]);

        // Actualizar los datos del salón
        $salon->update([
            'nombre' => $request->nombre,
            'idPiso' => $request->idPiso,
        ]);

        return redirect()->route('salones.gestion')->with('success', 'Salón actualizado con éxito.');
    }

    public function destroy(Salon $salon)
    {
        $salon->delete();

        return redirect()->route('salones.gestion')
            ->with('success', 'Salón eliminado correctamente');
    }


    public function getPisosPorEdificio($idEdificio)
    {
        $pisos = Piso::where('idEdificio', $idEdificio)->get(['id', 'numero']);
        return response()->json($pisos);
    }

    public function gestionSalones()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar salones, pisos y edificios de la institución
        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('piso', 'piso.edificio')->get();

        $pisos = Piso::whereHas('edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->get();

        $edificios = Edificio::where('id_institution', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('salones.gestion', compact('salones', 'pisos', 'edificios', 'name', 'nombre'));
    }

    public function actualizar(Request $request, $idSalon)
    {
        $salon = Salon::findOrFail($idSalon); // Busca el salón por idSalon

        $data = $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'regex:/^[a-zA-ZÁÉÍÓÚáéíóúÑñ0-9\s]+$/',
                Rule::unique('salones')->where(function ($query) use ($request) {
                    return $query->where('idPiso', $request->idPiso);
                })->ignore($salon->idSalon), // Ignora el ID actual para la validación única
            ],
            'idEdificio' => 'required|integer',
            'idPiso' => 'required|integer',
        ]);

        $salon->update($data);

        return redirect()->route('salones.gestion')->with('success', 'Salón actualizado correctamente.');
    }
}
