<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Maestro;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Evento;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class HorarioController extends Controller
{
    public function index()
    {
        $salones = Salon::with(['horarios' => function ($query) {
            $query->orderBy('dia')
                ->orderBy('horaInicio');
        }, 'horarios.grupo', 'horarios.maestro'])->get();

        return response()->json($salones, 200);
    }
    public function vista()
    {
        $salones = Salon::with(['horarios' => function ($query) {
            $query->orderBy('dia')
                ->orderBy('horaInicio');
        }, 'horarios.grupo', 'horarios.maestro'])->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        // Obtener los periodos únicos de los horarios
        $periodos = Horario::distinct()->pluck('periodo');

        return view('horarios.index', compact('salones', 'name', 'nombre', 'periodos'));
    }


    public function create()
    {
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $maestros = Maestro::all();
        $grupos = Grupo::all();
        $salones = Salon::all();

        return view('horarios.gestion', compact('maestros', 'grupos', 'salones', 'nombre', 'name'));
    }

    public function store(Request $request)
    {
        // Validación de los campos del formulario
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
            'idGrupo' => 'required|exists:grupos,idGrupo',
            'idSalon' => 'required|exists:salones,idSalon',
            'idMaestro' => 'required|exists:maestros,id',
            'periodo' => 'required|string'
        ]);

        // Validación de solapamiento de horarios
        $solapado = Horario::where('dia', $request->dia)
            ->where('idSalon', $request->idSalon)
            ->where(function ($query) use ($request) {
                $query->whereBetween('horaInicio', [$request->horaInicio, $request->horaFin])
                    ->orWhereBetween('horaFin', [$request->horaInicio, $request->horaFin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('horaInicio', '<=', $request->horaInicio)
                            ->where('horaFin', '>=', $request->horaFin);
                    });
            })
            ->exists();

        if ($solapado) {
            // Redirigir al formulario con un mensaje de error si el horario ya está ocupado
            return redirect()->route('horarios.create')  // Asegúrate de que esta ruta sea la correcta
                ->with('alert', 'La hora ya está ocupada.');
        }

        // Crear el nuevo horario
        $horario = Horario::create($request->all());

        // Redirigir a la vista de éxito con un mensaje de éxito
        return redirect()->route('horarios.gestion')  // Asegúrate de que esta ruta sea la correcta
            ->with('success', 'Horario registrado con éxito');
    }

    public function edit($id)
    {
        try {
            $horario = Horario::with(['maestro', 'grupo', 'salon'])->findOrFail($id);

            // Agrega el periodo si no está incluido
            $periodo = $horario->periodo ?? 'No definido'; // Ajusta según tu modelo

            // Responde con JSON
            return response()->json([
                'idHorario' => $horario->idHorario,
                'horaInicio' => \Carbon\Carbon::parse($horario->horaInicio)->format('H:i'),
                'horaFin' => \Carbon\Carbon::parse($horario->horaFin)->format('H:i'),
                'dia' => $horario->dia,
                'idGrupo' => $horario->idGrupo,
                'idMaestro' => $horario->idMaestro,
                'idSalon' => $horario->idSalon,
                'periodo' => $periodo,
                'grupo' => $horario->grupo->nombre ?? null,
                'maestro' => $horario->maestro->nombre ?? null,
                'salon' => $horario->salon->nombre ?? null,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Horario no encontrado o error interno'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        // Verificar los datos que se están recibiendo
        Log::info("Datos recibidos para la actualización:", $request->all());
        Log::info("ID del horario a actualizar: " . $id);

        // Validación de los datos
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
            'idGrupo' => 'required|exists:grupos,idGrupo',
            'idSalon' => 'required|exists:salones,idSalon',
            'idMaestro' => 'required|exists:maestros,id',
            'periodo' => 'required|string'
        ]);

        // Verificar si hay solapamiento de horarios
        $solapado = Horario::where('dia', $request->dia)
            ->where('idSalon', $request->idSalon)
            ->where('idHorario', '<>', $id) // Excluir el horario actual
            ->where(function ($query) use ($request) {
                $query->whereBetween('horaInicio', [$request->horaInicio, $request->horaFin])
                    ->orWhereBetween('horaFin', [$request->horaInicio, $request->horaFin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('horaInicio', '<=', $request->horaInicio)
                            ->where('horaFin', '>=', $request->horaFin);
                    });
            })
            ->exists();

        if ($solapado) {
            // Si hay solapamiento, redirigir con un mensaje de error
            return redirect()->route('horarios.gestion', ['id' => $id])
                ->with('alert', 'El horario se solapa con otro en el mismo salón y día.');
        }

        // Si no hay solapamiento, actualizamos el horario
        $horario = Horario::findOrFail($id);
        $horario->update($request->all());

        // Verificar si los datos se actualizaron correctamente
        Log::info("Horario actualizado con éxito:", $horario->toArray());

        // Redirigir a la vista de listado de horarios con un mensaje de éxito
        return redirect()->route('horarios.gestion')
            ->with('success', 'Horario actualizado con éxito');
    }


    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.gestion')->with('success', 'Piso eliminado correctamente.');
    }

    public function destroyByPeriodo($periodo)
    {
        try {
            // Buscar los horarios asociados al período
            $horarios = Horario::where('periodo', $periodo)->get();

            if ($horarios->isEmpty()) {
                return redirect()->route('horarios.gestion')->with('error', "No se encontraron horarios para el periodo: $periodo");
            }

            // Eliminar los horarios
            $deleted = Horario::where('periodo', $periodo)->delete();

            if ($deleted > 0) {
                return redirect()->route('horarios.gestion')->with('success', "Horarios del periodo '$periodo' eliminados correctamente.");
            } else {
                return redirect()->route('horarios.gestion')->with('error', "Ocurrió un error al intentar eliminar los horarios del periodo: $periodo");
            }
        } catch (\Exception $e) {
            return redirect()->route('horarios.gestion')->with('error', 'Ocurrió un error inesperado: ' . $e->getMessage());
        }
    }


    public function horariosDelMaestroPorDia($maestroId, $dia)
    {
        $horarios = Horario::where('idMaestro', $maestroId)
            ->where('dia', $dia)
            ->with(['grupo', 'salon'])
            ->get();

        return response()->json($horarios, 200);
    }

    public function horarioEnElMomentoDelSalon($salonId)
    {
        try {
            // Obtén la hora y el día actuales
            $horaActual = Carbon::now()->format('H:i:s'); // Incluye segundos
            $diaActual = Carbon::now()->locale('es')->isoFormat('dddd'); // Día en español

            // Consulta los horarios
            $horarios = Horario::where('idSalon', $salonId)
                ->where('dia', $diaActual)
                ->where('horaInicio', '<=', $horaActual)
                ->where('horaFin', '>=', $horaActual)
                ->with(['grupo', 'maestro'])
                ->get();

            // Depuración
            Log::info('Horarios encontrados: ' . $horarios->count());

            return response()->json($horarios, 200);
        } catch (\Exception $e) {
            Log::error('Error al obtener el horario del salón: ' . $e->getMessage());

            return response()->json(['error' => 'Hubo un error al obtener el horario del salón.'], 500);
        }
    }


    public function horarioDelSalonPorDia($salonId, $dia)
    {
        $horarios = Horario::where('idSalon', $salonId)
            ->where('dia', $dia)
            ->with(['grupo', 'maestro'])
            ->orderBy('horaInicio')
            ->get();

        return response()->json($horarios, 200);
    }

    public function horarioDelSalonPorSemana($salonId)
    {
        $horarios = Horario::where('idSalon', $salonId)
            ->with(['grupo', 'maestro'])
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->get();

        return response()->json($horarios, 200);
    }

    public function todosLosHorariosDisponiblesHoy()
{
    try {
        // Establece el idioma a español y obtiene la hora y el día actuales
        \Carbon\Carbon::setLocale('es');
        $horaActual = \Carbon\Carbon::now()->format('H:i'); // Hora actual con minutos
        $diaActual = \Carbon\Carbon::now()->isoFormat('dddd'); // Día actual en español

        // Depuración
        Log::info('Hora actual: ' . $horaActual);
        Log::info('Día actual: ' . $diaActual);

        // Consulta los horarios que están en progreso o que empiezan después
        $horarios = Horario::where('dia', $diaActual)
            ->where('horaInicio', '<=', $horaActual) // Comenzaron antes o justo ahora
            ->where('horaFin', '>=', $horaActual)    // Aún no han terminado
            ->with(['salon', 'grupo.materia', 'maestro'])   // Relaciones necesarias
            ->orderBy('horaInicio')                // Ordenar por hora de inicio
            ->get();

        // Mapeamos los horarios para estructurar mejor los datos
        $horariosConMaterias = $horarios->map(function ($horario) {
            return [
                'idHorario' => $horario->idHorario,
                'dia' => $horario->dia,
                'horaInicio' => $horario->horaInicio,
                'horaFin' => $horario->horaFin,
                'salon' => $horario->salon->nombre ?? null,
                'grupo' => [
                    'idGrupo' => $horario->grupo->idGrupo ?? null,
                    'nombre' => $horario->grupo->nombre ?? null,
                ],
                'materia' => $horario->grupo->materia->nombre ?? null,
                'maestro' => $horario->maestro->nombre ?? null,
            ];
        });

        // Respuesta JSON
        return response()->json([
            'data' => $horariosConMaterias,
            'message' => 'Horarios disponibles obtenidos correctamente.',
            'debug' => [
                'current_day' => $diaActual,
                'current_time' => $horaActual,
            ],
        ], 200);
    } catch (\Exception $e) {
        Log::error('Error al obtener los horarios: ' . $e->getMessage());

        return response()->json([
            'error' => 'Hubo un error al obtener los horarios disponibles.',
        ], 500);
    }
}



    public function eventosDelDia()
    {
        $diaActual = now()->format('Y-m-d'); // Fecha actual

        $eventos = Evento::whereDate('fecha', $diaActual)->get();

        return response()->json($eventos, 200);
    }

    public function downloadTemplate()
    {
        $filePath = storage_path('app/public/templates/horarios_template.xlsx');
        return response()->download($filePath, 'horarios_template.xlsx');
    }


    public function uploadExcel(Request $request)
    {
        // Validar que el archivo está presente y es un Excel
        $request->validate([
            'excelFile' => 'required|file|mimes:xlsx,xls',
        ]);

        $file = $request->file('excelFile');
        $errores = []; // Para acumular errores por fila
        $nuevosHorarios = 0;

        try {
            // Cargar el archivo Excel
            $spreadsheet = IOFactory::load($file->getPathname());
            $worksheet = $spreadsheet->getActiveSheet();
            $rows = $worksheet->toArray();

            foreach ($rows as $index => $row) {
                // Saltar la fila de encabezados
                if ($index === 0) {
                    continue;
                }

                try {
                    // Verificar si la fila está vacía (todas las celdas están vacías)
                    if (array_filter($row) === []) {
                        continue; // Saltar la fila si está completamente vacía
                    }

                    // Extraer datos con nombres claros
                    $horaInicio = $row[0] ?? null;
                    $horaFin = $row[1] ?? null;
                    $dia = $row[2] ?? null;
                    $grupoNombre = $row[3] ?? null;
                    $salonNombre = $row[5] ?? null;
                    $maestroNombre = $row[4] ?? null;
                    $periodo = $row[6] ?? null;

                    // Validar que todos los campos necesarios estén presentes
                    if (!$horaInicio || !$horaFin || !$dia || !$grupoNombre || !$salonNombre || !$maestroNombre || !$periodo) {
                        continue; // Saltar esta fila si falta algún dato
                    }

                    // Búsqueda por nombre: Grupo
                    $grupo = Grupo::where('nombre', trim($grupoNombre))->first();
                    if (!$grupo) {
                        throw new \Exception("No se encontró el grupo '{$grupoNombre}' en la fila " . ($index + 1));
                    }

                    // Búsqueda por nombre: Maestro
                    $maestro = Maestro::where('nombre', trim($maestroNombre))->first();
                    if (!$maestro) {
                        throw new \Exception("No se encontró el maestro '{$maestroNombre}' en la fila " . ($index + 1));
                    }

                    // Búsqueda por nombre: Salón
                    $salon = Salon::where('nombre', trim($salonNombre))->first();
                    if (!$salon) {
                        throw new \Exception("No se encontró el salón '{$salonNombre}' en la fila " . ($index + 1));
                    }

                    // Validar si ya existe un horario con los mismos datos
                    $existeHorario = Horario::where('dia', strtolower($dia))
                        ->where('idSalon', $salon->idSalon)
                        ->where('horaInicio', $horaInicio)
                        ->where('horaFin', $horaFin)
                        ->where('periodo', $periodo)
                        ->exists();

                    if ($existeHorario) {
                        throw new \Exception("El horario ya existe en la fila " . ($index + 1));
                    }

                    // Crear el horario si pasa todas las validaciones
                    Horario::create([
                        'horaInicio' => $horaInicio,
                        'horaFin' => $horaFin,
                        'dia' => strtolower($dia),
                        'idGrupo' => $grupo->idGrupo,
                        'idSalon' => $salon->idSalon,
                        'idMaestro' => $maestro->id,
                        'periodo' => $periodo,
                    ]);

                    $nuevosHorarios++;
                } catch (\Exception $e) {
                    // Capturar errores por fila y mostrarlos en la respuesta
                    $errores[] = $e->getMessage();
                }
            }

            // Verificar si hubo errores
            if (!empty($errores)) {
                // Redirigir con errores en sesión
                return redirect()->back()->withErrors($errores);
            }

            // Redirigir con mensaje de éxito
            return redirect()->back()->with('success', "$nuevosHorarios horarios subidos exitosamente.");
        } catch (\Exception $e) {
            // Redirigir con error general
            return redirect()->back()->with('error', 'Error al procesar el archivo. ' . $e->getMessage());
        }
    }


    public function gestionHorarios()
    {
        $horarios = Horario::with(['grupo.materia', 'maestro', 'salon'])->get();
        $salones = Salon::all();
        $grupos = Grupo::with('materia')->get();
        $maestros = Maestro::all();
        $periodos = Horario::distinct()->pluck('periodo'); // Obtener periodos únicos
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('horarios.gestion', compact('horarios', 'salones', 'grupos', 'maestros', 'periodos', 'name', 'nombre'));
    }

    public function dashboardStats()
    {
        try {
            $totalHorarios = Horario::count();
            $todayHorarios = Horario::where('dia', now()->format('l'))->count();
            $periodHorarios = Horario::distinct('periodo')->count();

            // Agrupación de horarios por día
            $horariosPorDia = Horario::selectRaw('dia, COUNT(*) as total')
                ->groupBy('dia')
                ->orderByRaw('FIELD(dia, "lunes", "martes", "miércoles", "jueves", "viernes", "sábado")')
                ->pluck('total', 'dia');

            // Agrupación de horarios por periodo
            $horariosPorPeriodo = Horario::selectRaw('periodo, COUNT(*) as total')
                ->groupBy('periodo')
                ->pluck('total', 'periodo');

            return response()->json([
                'totalHorarios' => $totalHorarios,
                'todayHorarios' => $todayHorarios,
                'periodHorarios' => $periodHorarios,
                'horariosPorDia' => $horariosPorDia,
                'horariosPorPeriodo' => $horariosPorPeriodo,
            ]);
        } catch (\Exception $e) {
            Log::error('Error al obtener estadísticas de horarios: ' . $e->getMessage());
            return response()->json(['error' => 'Error al obtener estadísticas de horarios.'], 500);
        }
    }

    //Materia
    public function materiasPorDia($dia)
    {
        $horarios = Horario::where('dia', strtolower($dia))
            ->with(['grupo.materia', 'maestro', 'salon'])
            ->get();

        $materias = $horarios->map(function ($horario) {
            return [
                'materia' => $horario->grupo->materia->nombre ?? 'No asignada',
                'maestro' => $horario->maestro->nombre ?? 'No asignado',
                'salon' => $horario->salon->nombre ?? 'No asignado',
                'horaInicio' => $horario->horaInicio,
                'horaFin' => $horario->horaFin,
            ];
        });

        return response()->json($materias, 200);
    }

    public function materiasPorSemana()
    {
        $horarios = Horario::with(['grupo.materia', 'maestro', 'salon'])
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->get();

        $materiasPorDia = $horarios->groupBy('dia')->map(function ($horarios) {
            return $horarios->map(function ($horario) {
                return [
                    'materia' => $horario->grupo->materia->nombre ?? 'No asignada',
                    'maestro' => $horario->maestro->nombre ?? 'No asignado',
                    'salon' => $horario->salon->nombre ?? 'No asignado',
                    'horaInicio' => $horario->horaInicio,
                    'horaFin' => $horario->horaFin,
                ];
            });
        });

        return response()->json($materiasPorDia, 200);
    }

    //carreras
    public function carrerasPorDia($dia)
    {
        $horarios = Horario::where('dia', strtolower($dia))
            ->with(['grupo.materia.carrera', 'maestro', 'salon'])
            ->get();

        $carreras = $horarios->map(function ($horario) {
            return [
                'carrera' => $horario->grupo->materia->carrera->nombre ?? 'No asignada',
                'materia' => $horario->grupo->materia->nombre ?? 'No asignada',
                'maestro' => $horario->maestro->nombre ?? 'No asignado',
                'salon' => $horario->salon->nombre ?? 'No asignado',
                'horaInicio' => $horario->horaInicio,
                'horaFin' => $horario->horaFin,
            ];
        });

        return response()->json($carreras, 200);
    }

    public function carrerasPorSemana()
    {
        $horarios = Horario::with(['grupo.materia.carrera', 'maestro', 'salon'])
            ->orderBy('dia')
            ->orderBy('horaInicio')
            ->get();

        $carrerasPorDia = $horarios->groupBy('dia')->map(function ($horarios) {
            return $horarios->map(function ($horario) {
                return [
                    'carrera' => $horario->grupo->materia->carrera->nombre ?? 'No asignada',
                    'materia' => $horario->grupo->materia->nombre ?? 'No asignada',
                    'maestro' => $horario->maestro->nombre ?? 'No asignado',
                    'salon' => $horario->salon->nombre ?? 'No asignado',
                    'horaInicio' => $horario->horaInicio,
                    'horaFin' => $horario->horaFin,
                ];
            });
        });

        return response()->json($carrerasPorDia, 200);
    }

    public function salonesDisponiblesMomento(Request $request)
    {
        try {
            // Obtener hora y día actuales si no se envían en la solicitud
            $horaActual = $request->input('hora', now()->format('H:i'));
            $diaActual = $request->input('dia', now()->format('Y-m-d')); // Día actual en formato de fecha

            // Buscar salones ocupados en este momento
            $salonesOcupados = Evento::whereDate('dia', $diaActual)
                ->where('hora_inicio', '<=', $horaActual)
                ->where('hora_fin', '>=', $horaActual)
                ->pluck('idSalon'); // Solo IDs de salones ocupados

            // Obtener salones disponibles (los que no están en la lista de ocupados)
            $salonesDisponibles = Salon::whereNotIn('idSalon', $salonesOcupados)->get();

            return response()->json($salonesDisponibles, 200);
        } catch (\Exception $e) {
            Log::error('Error en salonesDisponiblesMomento: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }

    public function salonesDisponiblesDia($dia)
    {
        try {
            // Buscar salones ocupados durante el día
            $salonesOcupados = Evento::whereDate('dia', $dia)
                ->pluck('idSalon'); // IDs de salones ocupados

            // Obtener salones disponibles (los que no están ocupados en todo el día)
            $salonesDisponibles = Salon::whereNotIn('idSalon', $salonesOcupados)->get();

            return response()->json($salonesDisponibles, 200);
        } catch (\Exception $e) {
            Log::error('Error en salonesDisponiblesDia: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
