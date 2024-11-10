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
        $salones = Salon::with('piso')->get();
        $edificios = Edificio::all();

        return view('salones.index', compact('edificios', 'salones', 'name', 'nombre'));
    }

    public function create()
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $pisos = Piso::all();
        $edificios = Edificio::all();

        return view('salones.create', compact('edificios', 'pisos', 'name', 'nombre'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'idPiso' => 'required|exists:pisos,id',
        ]);

        Salon::create($request->all());
        Edificio::create($request->all());
        return redirect()->route('salones.index')->with('success', 'Salón registrado correctamente');
    }

    public function edit($id)
    {
        $name = Session::get('usuario_name', 'Usuario no autenticado');
        $nombre = Session::get('institution_name', 'Institución no especificada');
        $salon = Salon::findOrFail($id);
        $pisos = Piso::all();
        $edificios = Edificio::all();

        return view('salones.edit', compact('edificios', 'salon', 'pisos', 'name', 'nombre'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nombre' => 'required|string|max:255',
            'idPiso' => 'required|exists:pisos,id',
        ]);

        $salon = Salon::findOrFail($id);
        $salon->update($request->all());
        $edificio = Edificio::findOrFail($id);
        $edificio->update($request->all());

        return redirect()->route('salones.index')->with('success', 'Salón actualizado correctamente');
    }

}
