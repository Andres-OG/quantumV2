<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class PisoController extends Controller
{
    public function index()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $pisos = Piso::with('edificio')->get();

        return view('pisos.index', compact('pisos', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $edificios = Edificio::all();

        return view('pisos.create', compact('edificios', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'numero' => [
                'required',
                'regex:/^(PB|[1-9])$/', // Solo permite "PB" o números del 1 al 9
            ],
            'edificio_id' => 'required|exists:edificios,id',
        ], [
            'numero.required' => 'El campo número es obligatorio.',
            'numero.regex' => 'El número solo puede ser "PB" (Planta Baja) o un número entre 1 y 9.',
            'edificio_id.required' => 'El campo edificio es obligatorio.',
            'edificio_id.exists' => 'El edificio seleccionado no es válido.',
        ]);

        Piso::create([
            'numero' => $request->numero,
            'edificio_id' => $request->edificio_id,
        ]);

        return redirect()->route('pisos.index')->with('success', 'Piso registrado correctamente');
    }

    public function edit(Piso $piso)
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $edificios = Edificio::all();

        return view('pisos.edit', compact('piso', 'edificios', 'name', 'nombre'));
    }

    public function update(Request $request, Piso $piso)
    {
        $request->validate([
            'numero' => [
                'required',
                'regex:/^(PB|[1-9])$/', // Solo permite "PB" o números del 1 al 9
            ],
            'edificio_id' => 'required|exists:edificios,id',
        ], [
            'numero.required' => 'El campo número es obligatorio.',
            'numero.regex' => 'El número solo puede ser "PB" (Planta Baja) o un número entre 1 y 9.',
            'edificio_id.required' => 'El campo edificio es obligatorio.',
            'edificio_id.exists' => 'El edificio seleccionado no es válido.',
        ]);

        $piso->update([
            'numero' => $request->numero,
            'edificio_id' => $request->edificio_id,
        ]);

        return redirect()->route('pisos.index')->with('success', 'Piso actualizado correctamente');
    }

    public function destroy(Piso $piso)
    {
        $piso->delete();

        return redirect()->route('pisos.index')->with('success', 'Piso eliminado correctamente');
    }
}
