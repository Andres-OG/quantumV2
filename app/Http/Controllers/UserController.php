<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function store(Request $request)
    {
        try {
            Log::info('Solicitud recibida: ', $request->all());

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'firstNameMale' => 'required|string|max:255',
                'firstNameFemale' => 'required|string|max:255',
                'email' => 'required|email|unique:users',
                'password' => 'required|string|min:8',
                'id_institution' => 'required|uuid|exists:institutions,id_institution',
                'id_role' => 'required|integer|exists:roles,id_role',
                'account_number' => 'nullable|required_if:id_role,3|digits:7|unique:users',
            ]);

            Log::info('Datos validados: ', $validatedData);

            $user = User::create([
                'name' => $validatedData['name'],
                'firstNameMale' => $validatedData['firstNameMale'],
                'firstNameFemale' => $validatedData['firstNameFemale'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'id_institution' => $validatedData['id_institution'],
                'id_role' => 3,
                'account_number' => $validatedData['account_number'],
            ]);

            Log::info('Usuario creado: ', $user->toArray());

            return response()->json(['message' => 'User registered successfully', 'user' => $user], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación: ', $e->errors());
        
            return response()->json([
                'message' => 'Errores de validación detectados.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            Log::error('Pila de errores: ' . $e->getTraceAsString());
        
            return response()->json([
                'message' => 'Ocurrió un error en el servidor.',
                'error' => $e->getMessage()
            ], 500);
        }        
    }

    public function updateUser(Request $request, $id_user)
    {
        try {
            Log::info('Iniciando actualización para el usuario con ID: ' . $id_user);
 
            // Validar los datos recibidos
            $validatedData = $request->validate([
                'name' => ['required', 'string', 'regex:/^[\pL\s]+$/u', 'min:3', 'max:40'],
                'email' => [
                    'required',
                    'email',
                    'max:100',
                    'regex:/^[a-zA-Z0-9._-]+@[a-zA-Z0-9-]+\.[a-zA-Z]{2,}$/',
                    'unique:users,email,' . $id_user . ',id_user', // Aquí indicamos la columna correcta
                ],
            ]);
 
            Log::info('Datos validados correctamente: ', $validatedData);
 
            // Buscar al usuario por su ID
            $user = User::findOrFail($id_user);
            Log::info('Usuario encontrado: ', $user->toArray());
 
            // Actualizar los datos del usuario
            $user->update([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
            ]);
 
            Log::info('Usuario actualizado exitosamente.');
 
            return response()->json([
                'message' => 'Usuario actualizado exitosamente.',
                'user' => $user,
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Errores de validación: ', $e->errors());
            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {
            Log::error('Error general: ' . $e->getMessage());
            return response()->json(['error' => 'Error interno del servidor.'], 500);
        }
    }

    public function login(Request $request)
    {
        // Validar la solicitud
        $validatedData = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // Intentar encontrar al usuario
        $user = User::where('email', $validatedData['email'])->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && Hash::check($validatedData['password'], $user->password)) {
            // Verificar si la institución está activa
            if ($user->institution && !$user->institution->status) {
                return response()->json([
                    'message' => 'La institución está inactiva. Por favor, espera a que se active.',
                ], 403);
            }

            // Autenticar al usuario
            Auth::login($user);

            // Retornar respuesta JSON con datos del usuario
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'id_institution' => $user->id_institution,
                ],
            ], 200);
        }

        // Si la autenticación falla, retorna un mensaje de error
        return response()->json([
            'message' => 'Credenciales incorrectas',
        ], 401);
    }

    public function logout(Request $request)
    {
        // Cierra la sesión del usuario autenticado
        Auth::logout();

        // Retorna respuesta JSON confirmando el cierre de sesión
        return response()->json([
            'message' => 'Sesión cerrada exitosamente',
        ], 200);
    }
}
