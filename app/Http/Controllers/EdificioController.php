<?php

namespace App\Http\Controllers;

use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class EdificioController extends Controller
{
    public function index()
    {
        $edificios = Edificio::all();
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución');

        return view('edificios.index', compact('edificios', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('edificios.create', compact('name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:15',
                'min:1',
                'regex:/^[a-zA-Z\s]+$/', // Solo letras y espacios
            ],
        ], [
            'nombre.required' => 'El nombre del edificio es obligatorio.',
            'nombre.max' => 'El nombre del edificio no puede exceder los 15 caracteres.',
            'nombre.min' => 'El nombre del edificio debe tener al menos 1 carácter.',
            'nombre.regex' => 'El nombre del edificio solo debe contener letras y espacios.',
        ]);

        Edificio::create(['nombre' => $request->nombre]);

        return redirect()->route('edificios.index')->with('success', 'Edificio registrado correctamente');
    }

    public function edit($id)
    {
        $edificio = Edificio::findOrFail($id);
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');

        return view('edificios.edit', compact('edificio', 'name', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:15',
                'min:1',
                'regex:/^[a-zA-Z\s]+$/', // Solo letras y espacios
            ],
        ], [
            'nombre.required' => 'El nombre del edificio es obligatorio.',
            'nombre.max' => 'El nombre del edificio no puede exceder los 15 caracteres.',
            'nombre.min' => 'El nombre del edificio debe tener al menos 1 carácter.',
            'nombre.regex' => 'El nombre del edificio solo debe contener letras y espacios.',
        ]);

        $edificio = Edificio::findOrFail($id);
        $edificio->nombre = $request->nombre;
        $edificio->save();

        return redirect()->route('edificios.index')->with('success', 'Edificio actualizado correctamente');
    }

    public function destroy($id)
    {
        $edificio = Edificio::findOrFail($id);
        $edificio->delete();

        return redirect()->route('edificios.index')->with('success', 'Edificio eliminado correctamente');
    }
}
