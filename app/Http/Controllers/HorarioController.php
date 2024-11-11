<?php

namespace App\Http\Controllers;

use App\Models\Horario;
use App\Models\Grupo;
use App\Models\Salon;
use App\Models\Maestro;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class HorarioController extends Controller
{
    public function index()
    {
        $salones = Salon::with(['horarios' => function ($query) {
            $query->orderBy('dia')
                ->orderBy('horaInicio');
        }, 'horarios.grupo', 'horarios.maestro'])->get();

        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        // Obtener los periodos únicos de los grupos
        $periodos = Grupo::distinct()->pluck('periodo');

        return view('horarios.index', compact('salones', 'name', 'nombre', 'periodos'));
    }


    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        $grupos = Grupo::all();
        $salones = Salon::all();
        $maestros = Maestro::all();

        return view('horarios.create', compact('name', 'nombre', 'grupos', 'salones', 'maestros'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
            'idGrupo' => 'required|exists:grupos,idGrupo',
            'idSalon' => 'required|exists:salones,idSalon',
            'idMaestro' => 'required|exists:maestros,id',
        ]);

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
            return back()->withErrors(['horario' => 'El horario se solapa con otro en el mismo salón y día.'])->withInput();
        }

        Horario::create([
            'horaInicio' => $request->horaInicio,
            'horaFin' => $request->horaFin,
            'dia' => $request->dia,
            'idGrupo' => $request->idGrupo,
            'idSalon' => $request->idSalon,
            'idMaestro' => $request->idMaestro,
        ]);

        return redirect()->route('horarios.index')->with('success', 'Horario registrado con éxito');
    }

    public function edit($id)
    {
        $horario = Horario::findOrFail($id);
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        $grupos = Grupo::with('materia')->get();
        $salones = Salon::all();
        $maestros = Maestro::all();

        return view('horarios.edit', compact('horario', 'name', 'nombre', 'grupos', 'salones', 'maestros'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'horaInicio' => 'required|date_format:H:i',
            'horaFin' => 'required|date_format:H:i|after:horaInicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes,sábado',
            'idGrupo' => 'required|exists:grupos,idGrupo',
            'idSalon' => 'required|exists:salones,idSalon',
            'idMaestro' => 'required|exists:maestros,id',
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update([
            'horaInicio' => $request->horaInicio,
            'horaFin' => $request->horaFin,
            'dia' => $request->dia,
            'idGrupo' => $request->idGrupo,
            'idSalon' => $request->idSalon,
            'idMaestro' => $request->idMaestro,
        ]);

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado con éxito');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado con éxito');
    }

}
