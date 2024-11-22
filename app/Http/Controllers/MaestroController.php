<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Maestro;
use App\Models\Carrera;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class MaestroController extends Controller
{
    public function index()
    {
        // Obtener el ID de la institución del usuario autenticado
        $institutionId = Auth::user()->id_institution;

        // Obtener los maestros cuya carrera pertenece a la institución
        $maestros = Maestro::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        return response()->json($maestros);
    }

    public function vista()
    {
        // Obtener el ID de la institución del usuario autenticado
        $institutionId = Auth::user()->id_institution;

        // Obtener los maestros cuya carrera pertenece a la institución
        $maestros = Maestro::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.index', compact('maestros', 'name', 'nombre'));
    }

    public function edit(Maestro $maestro)
    {
        $institutionId = Auth::user()->id_institution;

        // Validar que el maestro pertenece a la institución
        if ($maestro->carrera->created_by !== $institutionId) {
            abort(403, 'No tienes permiso para editar este maestro.');
        }

        // Obtener las carreras de la institución
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.gestion', compact('maestro', 'carreras', 'nombre'));
    }


    public function show(Maestro $maestro)
    {
        // Obtener un maestro específico con su carrera
        return response()->json($maestro->load('carrera'));
    }

    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Obtener solo las carreras de la institución
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.gestion', compact('carreras', 'name', 'nombre'));
    }


    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => [
                    'required',
                    'string',
                    'max:255',
                    'regex:/^[\p{L}\s\-\'\.,]+$/u',
                ],
                'no_cuenta' => [
                    'required',
                    'digits:7',
                    'unique:maestros,no_cuenta',
                ],
                'carrera_id' => 'required|exists:carreras,id',
            ], [
                'nombre.required' => 'El campo nombre es obligatorio.',
                'nombre.regex' => 'El nombre solo puede contener letras, espacios y caracteres válidos.',
                'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
                'no_cuenta.required' => 'El número de cuenta es obligatorio.',
                'no_cuenta.digits' => 'El número de cuenta debe tener exactamente 7 dígitos.',
                'no_cuenta.unique' => 'Ya existe un maestro registrado con este número de cuenta.',
                'carrera_id.required' => 'Debe seleccionar una carrera.',
                'carrera_id.exists' => 'La carrera seleccionada no es válida.',
            ]);

            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            Maestro::create($request->all());

            return redirect()->route('maestros.gestion')->with('success', 'Maestro registrado con éxito');
        } catch (\Exception $e) {
            return back()->with('error', 'Hubo un error al registrar el maestro.')->withInput();
        }
    }

    public function update(Request $request, Maestro $maestro)
    {
        $validator = Validator::make($request->all(), [
            'nombre' => [
                'required',
                'string',
                'max:255',
                'regex:/^[\p{L}\s\-\'\.,]+$/u',
            ],
            'no_cuenta' => [
                'required',
                'digits:7',
                'unique:maestros,no_cuenta,' . $maestro->id,
            ],
            'carrera_id' => 'required|exists:carreras,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.regex' => 'El nombre solo puede contener letras, espacios y caracteres válidos.',
            'nombre.max' => 'El nombre no puede exceder los 255 caracteres.',
            'no_cuenta.required' => 'El número de cuenta es obligatorio.',
            'no_cuenta.digits' => 'El número de cuenta debe tener exactamente 7 dígitos.',
            'no_cuenta.unique' => 'Ya existe un maestro registrado con este número de cuenta.',
            'carrera_id.required' => 'Debe seleccionar una carrera.',
            'carrera_id.exists' => 'La carrera seleccionada no es válida.',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        $maestro->update($request->all());

        return redirect()->route('maestros.gestion')->with('success', 'Maestro actualizado con éxito');
    }

    public function destroy(Maestro $maestro)
    {
        $maestro->delete();
        return redirect()->route('maestros.gestion')->with('success', 'Maestro eliminado con éxito');
    }

    public function gestionMaestros()
    {
        $institutionId = Auth::user()->id_institution;

        // Obtener los maestros cuya carrera pertenece a la institución
        $maestros = Maestro::whereHas('carrera', function ($query) use ($institutionId) {
            $query->where('created_by', $institutionId);
        })->with('carrera')->get();

        // Obtener las carreras que pertenecen a la institución
        $carreras = Carrera::where('created_by', $institutionId)->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('maestros.gestion', compact('maestros', 'carreras', 'name', 'nombre'));
    }

    public function dashboardStats()
    {
        try {
            // Obtener el ID de la institución del usuario autenticado
            $institutionId = Auth::user()->id_institution;

            // Filtrar maestros por institución
            $maestros = Maestro::whereHas('carrera', function ($query) use ($institutionId) {
                $query->where('created_by', $institutionId);
            });

            // Total de maestros de la institución
            $totalMaestros = $maestros->count();

            // Nuevos maestros en el último mes de la institución
            $nuevosMaestrosMes = $maestros->where('created_at', '>=', now()->subMonth())->count();

            // Crecimiento porcentual de maestros en el último mes
            $crecimientoMaestros = $nuevosMaestrosMes > 0
                ? round(($nuevosMaestrosMes / max(1, $totalMaestros)) * 100, 2)
                : 0;

            // Distribución de maestros por carrera dentro de la institución
            $maestrosPorCarrera = Maestro::whereHas('carrera', function ($query) use ($institutionId) {
                $query->where('created_by', $institutionId);
            })
                ->selectRaw('carrera_id, COUNT(*) as total')
                ->groupBy('carrera_id')
                ->get()
                ->pluck('total', 'carrera_id')
                ->mapWithKeys(function ($total, $carrera_id) {
                    $carrera = Carrera::find($carrera_id);
                    return [$carrera->nombre ?? 'Sin Carrera' => $total];
                });

            return response()->json([
                'totalMaestros' => $totalMaestros,
                'nuevosMaestrosMes' => $nuevosMaestrosMes,
                'crecimientoMaestros' => $crecimientoMaestros,
                'maestrosPorCarrera' => $maestrosPorCarrera,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener las estadísticas de los maestros: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener las estadísticas de los maestros.'], 500);
        }
    }
}
