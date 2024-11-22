<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class EventoController extends Controller
{
    // Obtener todos los eventos
    public function index()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar eventos por salones relacionados con la institución
        $eventos = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('salon')->get();

        return response()->json($eventos);
    }

    public function show($id)
    {
        $institutionId = Auth::user()->id_institution;

        $evento = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('salon')->find($id);

        if (!$evento) {
            return response()->json(['error' => 'Evento no encontrado'], 404);
        }

        return response()->json($evento);
    }

    public function vista()
    {
        $institutionId = Auth::user()->id_institution;

        // Filtrar eventos para la vista
        $eventos = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('salon')->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('eventos.index', compact('eventos', 'nombre'));
    }

    public function create()
    {
        $institutionId = Auth::user()->id_institution;

        // Obtener solo los salones de la institución del usuario
        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('eventos.create', compact('name', 'nombre', 'salones'));
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'nombre' => [
                    'required',
                    'string',
                    'max:50',
                    'regex:/^[a-zA-ZÀ-ÿ\s,]+$/u', // Permitir letras, espacios y comas
                ],
                'hora_inicio' => 'required|date_format:H:i',
                'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
                'dia' => 'required|date|after_or_equal:today', // Validar que la fecha no sea anterior a hoy
                'idSalon' => 'required|exists:salones,idSalon',
            ]);

            if (!$this->validarHorario($request->hora_inicio, $request->hora_fin)) {
                return back()->withErrors(['error' => 'El horario de inicio debe ser anterior al horario de fin, considerando cruces de medianoche.'])->withInput();
            }

            // Crear el evento
            Evento::create([
                'nombre' => $request->nombre,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'dia' => $request->dia,
                'idSalon' => $request->idSalon,
            ]);

            return redirect()->route(route: 'eventos.gestion')->with('success', 'Evento creado con éxito.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Redirigir con errores de validación
            return back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Redirigir con mensaje de error general
            return back()->withErrors(['error' => 'Ocurrió un error al crear el evento: ' . $e->getMessage()]);
        }
    }

    public function edit($id)
    {
        $institutionId = Auth::user()->id_institution;

        $evento = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->findOrFail($id);

        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('eventos.edit', compact('evento', 'salones', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->merge([
            'hora_inicio' => date('H:i', strtotime($request->hora_inicio)),
            'hora_fin' => date('H:i', strtotime($request->hora_fin)),
        ]);

        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^[a-zA-ZÀ-ÿ\s]+$/u',
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'dia' => 'required|date|after_or_equal:today', // Validar que la fecha no sea anterior a hoy
            'idSalon' => 'required|exists:salones,idSalon',
        ], [
            'hora_inicio.date_format' => 'El campo hora inicio debe coincidir con el formato HH:mm.',
            'hora_fin.date_format' => 'El campo hora fin debe coincidir con el formato HH:mm.',
            'idSalon.exists' => 'El salón seleccionado no es válido.',
        ]);

        // Verificar si el salón ya está reservado
        $conflicto = Evento::where('idSalon', $request->idSalon)
            ->whereDate('dia', $request->dia)
            ->where('id', '!=', $id)
            ->where(function ($query) use ($request) {
                $query->whereBetween('hora_inicio', [$request->hora_inicio, $request->hora_fin])
                    ->orWhereBetween('hora_fin', [$request->hora_inicio, $request->hora_fin])
                    ->orWhere(function ($query) use ($request) {
                        $query->where('hora_inicio', '<=', $request->hora_inicio)
                            ->where('hora_fin', '>=', $request->hora_fin);
                    });
            })
            ->exists();

        if ($conflicto) {
            return back()->withErrors(['error' => 'Este salón ya está reservado en ese horario.'])->withInput();
        }

        // Validación personalizada para horarios
        if (!$this->validarHorario($request->hora_inicio, $request->hora_fin)) {
            return back()->withErrors(['error' => 'El horario de inicio debe ser anterior al horario de fin, considerando cruces de medianoche.'])->withInput();
        }

        try {
            // Actualizar el evento
            $evento = Evento::findOrFail($id);
            $evento->update([
                'nombre' => $request->nombre,
                'hora_inicio' => $request->hora_inicio,
                'hora_fin' => $request->hora_fin,
                'dia' => $request->dia,
                'idSalon' => $request->idSalon,
            ]);

            return redirect()->route('eventos.gestion')->with('success', 'Evento actualizado con éxito.');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'No se pudo actualizar el evento: ' . $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $institutionId = Auth::user()->id_institution;

        $evento = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->findOrFail($id);

        $evento->delete();

        return redirect()->route('eventos.gestion')->with('success', 'Evento eliminado con éxito.');
    }

    public function gestion()
    {
        $institutionId = Auth::user()->id_institution;

        $eventos = Evento::whereHas('salon.piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->with('salon')->get();

        $salones = Salon::whereHas('piso.edificio', function ($query) use ($institutionId) {
            $query->where('id_institution', $institutionId);
        })->get();

        return view('eventos.gestion', compact('eventos', 'salones'));
    }


    public function showEvents($id)
    {
        $evento = Evento::with('salon')->findOrFail($id); // Recupera el evento con su relación de salón
        return response()->json($evento);
    }

    private function validarHorario($horaInicio, $horaFin)
    {
        // Convertir las horas a timestamps
        $horaInicioTimestamp = strtotime($horaInicio);
        $horaFinTimestamp = strtotime($horaFin);

        // Si la hora de fin es menor o igual que la hora de inicio, asumimos que cruza la medianoche
        if ($horaFinTimestamp <= $horaInicioTimestamp) {
            $horaFinTimestamp += 24 * 60 * 60; // Añadimos 24 horas a la hora de fin
        }

        return $horaFinTimestamp > $horaInicioTimestamp;
    }

    public function eventosPorDia($dia)
    {
        try {
            // Obtener eventos para el día específico
            $eventos = Evento::whereDate('dia', $dia)
                ->with(['salon']) // Cargar relación con salón
                ->orderBy('hora_inicio') // Ordenar por hora de inicio
                ->get();
 
            // Retornar los eventos en formato JSON
            return response()->json($eventos, 200);
        } catch (\Exception $e) {
            // Log del error para depuración
            Log::error('Error en eventosPorDia: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
 
    public function eventosPorSemana($startDate, $endDate)
    {
        try {
            $eventos = Evento::whereBetween('dia', [$startDate, $endDate])
                ->with(['salon'])
                ->orderBy('dia')
                ->orderBy('hora_inicio')
                ->get();
 
            // Agrupar eventos por día
            $eventosPorDia = $eventos->groupBy('dia');
 
            return response()->json($eventosPorDia, 200);
        } catch (\Exception $e) {
            Log::error('Error en eventosPorSemana: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor'], 500);
        }
    }
}
