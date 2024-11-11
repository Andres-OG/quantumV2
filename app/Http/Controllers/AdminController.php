<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Support\Facades\Session;

class AdminController extends Controller
{
    public function showRegisterForm(Request $request)
    {
        $institutionName = Session::get('institution_name');
        $institution = Institution::where('name', $institutionName)->first();

        if ($institution) {
            $idInstitution = $institution->id_institution; 
            return view('registerAdmin', [
                'id_institution' => $idInstitution 
            ])->with('status', 'Pago realizado y registro completado.');
        } else {
            return redirect()->route('payment.show')->withErrors('Institución no encontrada.');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'firstNameMale' => 'required|string|max:255',
            'firstNameFemale' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_institution' => 'required|exists:institutions,id_institution',
            'id_role' => 'nullable|exists:roles,id_role',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'firstNameMale' => $request->firstNameMale,
            'firstNameFemale' => $request->firstNameFemale,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_institution' => $request->id_institution,
            'id_role' => $request->id_role ?? 2,
            'status' => $request->status ?? false,
        ]);

        session(['usuario_name' => $admin->name]);
        return redirect()->route('confirmation')->with('status', 'Registro completado con éxito.');
    }

    public function updateStatus(Request $request, $id_user)
    {
        $user = User::findOrFail($id_user);
        $user->status = $request->input('status');
        $user->save();

        return redirect()->back()->with('status', 'Estado del usuario actualizado correctamente.');
    }

    public function destroy($id_user)
    {
        $user = User::where('id_user', $id_user)->first();

        if ($user) {
            $user->delete();
            return redirect()->route('dashboard')->with('status', 'Usuario eliminado con éxito.');
        }

        return redirect()->route('dashboard')->with('error', 'Usuario no encontrado.');
    }
}
