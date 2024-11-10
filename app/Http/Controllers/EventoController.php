<?php

namespace App\Http\Controllers;

use App\Models\Evento;
use App\Models\Salon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EventoController extends Controller
{
    public function index()
    {
        $eventos = Evento::all();
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');

        return view('eventos.index', compact('eventos', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        $salones = Salon::all(); // Recupera todos los salones para la selección en el formulario

        return view('eventos.create', compact('name', 'nombre', 'salones'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'regex:/^(?!.*[A-Z]{2,})(?=.*[a-zA-Z])[a-zA-Z0-9\s]*$/',
                'max:30'
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes',
            'idSalon' => 'required|exists:salones,id', // Validación para el salón
        ], [
            'dia.in' => 'El día debe ser lunes, martes, miércoles, jueves o viernes.',
            'idSalon.exists' => 'El salón seleccionado no es válido.',
        ]);

        Evento::create($request->all());
        return redirect()->route('eventos.index')->with('success', 'Evento registrado con éxito');
    }

    public function edit(Evento $evento)
    {
        $name = Session::get('usuario_name', 'Usuario');
        $nombre = Session::get('institution_name', 'Institución');
        $salones = Salon::all(); // Recupera todos los salones para la selección en el formulario

        return view('eventos.edit', compact('evento', 'name', 'nombre', 'salones'));
    }

    public function update(Request $request, Evento $evento)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:50',
                'regex:/^(?![A-Z\s]*$)[a-zA-Z0-9\s]+$/',
            ],
            'hora_inicio' => 'required|date_format:H:i',
            'hora_fin' => 'required|date_format:H:i|after:hora_inicio',
            'dia' => 'required|in:lunes,martes,miércoles,jueves,viernes',
            'idSalon' => 'required|exists:salones,id', // Validación para el salón
        ], [
            'dia.in' => 'El día debe ser lunes, martes, miércoles, jueves o viernes.',
            'idSalon.exists' => 'El salón seleccionado no es válido.',
        ]);

        $evento->update($request->all());

        return redirect()->route('eventos.index')->with('success', 'Evento actualizado con éxito');
    }

    public function destroy(Evento $evento)
    {
        $evento->delete();

        return redirect()->route('eventos.index')->with('success', 'Evento eliminado con éxito');
    }
}
