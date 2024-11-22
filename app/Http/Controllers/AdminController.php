<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Institution;

class AdminController extends Controller
{
    // Muestra el formulario de registro de usuario y verifica la institución
    public function showRegisterForm(Request $request)
    {
        $institutionName = session('institution_name');
        if (!$institutionName) {
            return redirect()->route('payment')->with('error', 'No se encontró una institución registrada.');
        }

        $institution = Institution::where('name', $institutionName)->firstOrFail();
        return view('registerAdmin', ['id_institution' => $institution->id_institution]);
    }

    // Registra un nuevo administrador
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:40'],
            'firstNameMale' => ['required', 'string', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:40'],
            'firstNameFemale' => ['required', 'string', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:40'],
            'email' => [
                'required',
                'email',
                'max:100',
                'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/',
                'unique:users',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'max:64',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
                'confirmed',
            ],
            'id_institution' => ['required', 'exists:institutions,id_institution'],
            'id_role' => ['nullable', 'integer', 'min:1', 'max:3'], // Asegura que el rol sea válido
        ], [
            'name.regex' => 'El nombre solo puede contener letras, espacios y tildes.',
            'firstNameMale.regex' => 'El primer apellido solo puede contener letras, espacios y tildes.',
            'firstNameFemale.regex' => 'El segundo apellido solo puede contener letras, espacios y tildes.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'firstNameMale.min' => 'El primer apellido debe tener al menos 3 caracteres.',
            'firstNameFemale.min' => 'El segundo apellido debe tener al menos 3 caracteres.',
            'email.regex' => 'El correo electrónico no es válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.regex' => 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula, un número y un carácter especial.',
        ]);

        User::create([
            'name' => $request->name,
            'firstNameMale' => $request->firstNameMale,
            'firstNameFemale' => $request->firstNameFemale,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'id_institution' => $request->id_institution,
            'id_role' => $request->id_role ?? 2,
            'status' => false,
        ]);
        session(['step_completed' => 'admin_registered']);
        return redirect()->route('confirmation')->with('success', 'Usuario registrado exitosamente.');
    }

    // Actualiza el estado de un usuario
    public function updateStatus(Request $request, $id_user)
    {
        $request->validate(['status' => 'required|boolean']);

        $user = User::findOrFail($id_user);
        $user->update(['status' => $request->status]);

        return response()->json([
            'message' => 'Estado del usuario actualizado correctamente.',
            'user' => $user,
        ], 200);
    }

    // Elimina un usuario de la base de datos
    public function destroy($id_user)
    {
        $user = User::findOrFail($id_user);
        $user->delete();

        return response()->json(['message' => 'Usuario eliminado con éxito.'], 200);
    }

    // Devuelve una lista de todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }
}
