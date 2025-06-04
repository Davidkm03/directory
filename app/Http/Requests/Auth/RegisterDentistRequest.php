<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterDentistRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'specialty' => 'required|string|max:100',
            'license_number' => 'required|string|max:50|unique:users,license_number',
            'biography' => 'nullable|string|max:1000',
            'profile_photo' => 'nullable|image|max:2048', // 2MB max
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre es obligatorio.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe tener un formato válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'La contraseña es obligatoria.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.confirmed' => 'La confirmación de la contraseña no coincide.',
            'specialty.required' => 'La especialidad es obligatoria.',
            'license_number.required' => 'El número de licencia profesional es obligatorio.',
            'license_number.unique' => 'Este número de licencia ya está registrado.',
            'profile_photo.image' => 'El archivo debe ser una imagen.',
            'profile_photo.max' => 'La imagen no debe pesar más de 2MB.',
        ];
    }
}
