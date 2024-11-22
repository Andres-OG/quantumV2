<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreUserRequest extends FormRequest
{
    /**
     * Determina si el usuario está autorizado a realizar esta solicitud.
     */
    public function authorize(): bool
    {
        // Permite que cualquier usuario acceda a esta solicitud
        return true;
    }

    /**
     * Obtiene las reglas de validación para la solicitud.
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|min:3|max:40|regex:/^[\pL\s]+$/u',
            'firstNameMale' => 'required|string|min:3|max:40|regex:/^[\pL\s]+$/u',
            'firstNameFemale' => 'required|string|min:3|max:40|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:100|unique:users',
            'password' => 'required|string|min:8|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/',
            'id_institution' => 'required|exists:institutions,id_institution',
            'account_number' => 'nullable|required_if:id_role,3|digits:7|unique:users',
        ];
    }

    /**
     * Mensajes de error personalizados (opcional).
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'name.regex' => 'El nombre solo puede contener letras y espacios.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede tener más de 40 caracteres.',
            'firstNameMale.required' => 'El nombre del padre es obligatorio.',
            'firstNameMale.regex' => 'El nombre del padre solo puede contener letras y espacios.',
            'firstNameMale.min' => 'El nombre del padre debe tener al menos 3 caracteres.',
            'firstNameMale.max' => 'El nombre del padre no puede tener más de 40 caracteres.',
            'firstNameFemale.required ' => 'El nombre de la madre es obligatorio.',
            'firstNameFemale.regex' => 'El nombre de la madre solo puede contener letras y espacios.',
            'firstNameFemale.min' => 'El nombre de la madre debe tener al menos 3 caracteres.',
            'firstNameFemale.max' => 'El nombre de la madre no puede tener más de 40 caracteres.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser una dirección de correo electrónico válida.',
            'email.max' => 'El correo electrónico no puede tener más de 100 caracteres.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.regex' => 'La contraseña debe tener al menos una mayúscula, un número y un carácter especial.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
            'id_institution.required' => 'La institución es obligatoria.',
            'id_institution.exists' => 'La institución seleccionada no es válida.',
            'account_number.required_if' => 'El número de cuenta es obligatorio para los usuarios.',
            'account_number.digits' => 'El número de cuenta debe tener 7 dígitos.',
            'account_number.unique' => 'Este número de cuenta ya está registrado.',
        ];
    }
}
