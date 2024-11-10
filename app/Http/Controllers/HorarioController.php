<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Horario;
use App\Models\Maestro;
use App\Models\Grupo;
use App\Models\Salon;
use Illuminate\Support\Facades\Session;

class HorarioController extends Controller
{
    public function index()
    {
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $horarios = Horario::with(['maestro', 'grupo', 'salon'])->get();

        return view('horarios.index', compact('horarios', 'nombre', 'name'));
    }

    public function create()
    {
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $maestros = Maestro::all();
        $grupos = Grupo::all();
        $salones = Salon::all();

        return view('horarios.create', compact('maestros', 'grupos', 'salones', 'nombre', 'name'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'horaInicio' => 'required|string|max:255',
            'horaFin' => 'required|string|max:255',
            'dia' => 'required|string|max:255',
            'idMaestro' => 'required|exists:maestros,id',
            'idGrupo' => 'required|exists:grupos,id',
            'idSalon' => 'required|exists:salones,id'
        ]);

        Horario::create($request->all());
        return redirect()->route('horarios.index')->with('success', 'Horario registrado correctamente');
    }

    public function edit($id)
    {
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $horario = Horario::findOrFail($id);
        $maestros = Maestro::all();
        $grupos = Grupo::all();
        $salones = Salon::all();

        return view('horarios.edit', compact('horario', 'maestros', 'grupos', 'salones', 'nombre', 'name'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'horaInicio' => 'required|string|max:255',
            'horaFin' => 'required|string|max:255',
            'dia' => 'required|string|max:255',
            'idMaestro' => 'required|exists:maestros,id',
            'idGrupo' => 'required|exists:grupos,id',
            'idSalon' => 'required|exists:salones,id'
        ]);

        $horario = Horario::findOrFail($id);
        $horario->update($request->all());

        return redirect()->route('horarios.index')->with('success', 'Horario actualizado correctamente');
    }

    public function destroy($id)
    {
        $horario = Horario::findOrFail($id);
        $horario->delete();

        return redirect()->route('horarios.index')->with('success', 'Horario eliminado correctamente');
    }
}
