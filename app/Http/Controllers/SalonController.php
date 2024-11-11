<?php

namespace App\Http\Controllers;

use App\Models\Piso;
use App\Models\Salon;
use App\Models\Edificio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class SalonController extends Controller
{
    public function index()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $salones = Salon::with('piso', 'piso.edificio')->get();

        return view('salones.index', compact('salones', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $edificios = Edificio::all();

        return view('salones.create', compact('edificios', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'regex:/^(?!.*[A-Z]{2,})(?=.*[a-zA-Z])[a-zA-Z0-9\s]*$/',
            ],
            'idPiso' => 'required|exists:pisos,id',
        ], [
            'nombre.required' => 'El campo nombre es obligatorio.',
            'nombre.max' => 'El nombre del salón no puede exceder los 20 caracteres.',
            'nombre.regex' => 'El nombre debe contener letras y números y no estar en mayúsculas completas.',
            'idPiso.required' => 'El campo piso es obligatorio.',
            'idPiso.exists' => 'El piso seleccionado no es válido.',
        ]);

        Salon::create([
            'nombre' => $request->nombre,
            'idPiso' => $request->idPiso,
        ]);

        return redirect()->route('salones.index')->with('success', 'Salón registrado correctamente');
    }

    public function edit($id)
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $salon = Salon::findOrFail($id);
        $edificios = Edificio::all();
        $pisos = Piso::where('edificio_id', $salon->piso->edificio_id)->get();

        return view('salones.edit', compact('salon', 'edificios', 'pisos', 'name', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => [
                'required',
                'string',
                'max:20',
                'regex:/^(?!.*[A-Z]{2,})(?=.*[a-zA-Z])[a-zA-Z0-9\s]*$/',
            ],
            'idPiso' => 'required|exists:pisos,id',
        ]);

        $salon = Salon::findOrFail($id);
        $salon->update([
            'nombre' => $request->nombre,
            'idPiso' => $request->idPiso,
        ]);

        return redirect()->route('salones.index')->with('success', 'Salón actualizado correctamente');
    }

    public function destroy($id)
    {
        $salon = Salon::findOrFail($id);
        $salon->delete();

        return redirect()->route('salones.index')->with('success', 'Salón eliminado correctamente');
    }

    public function getPisosPorEdificio($idEdificio)
    {
        return Piso::where('edificio_id', $idEdificio)->get();
    }
}
