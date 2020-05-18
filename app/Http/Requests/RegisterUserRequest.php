<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class RegisterUserRequest extends FormRequest
{
    /**
     * Determinar si el usuario está autorizado para hacer esta solicitud.
     * Por default es false, se debe cambiar a true sino lleva una lógica de validación
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => 'required|string|between:3,70|regex:/^[a-zA-ZñÑáÁéÉíÍóÓúÚ\s]+$/i',
            'email' => 'required|email|between:3,60|unique:users',
            'password' => 'required|between:8,255',
            'role' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'email.email' => 'No es un formato correcto.',
            'email.unique' => 'Ya existe una cuenta con éste email.',
            'name.string' => 'El nombre solo debe contener letras (string).',
            'name.regex' => 'El campo nombre solo debe contener letras.',
            'between' => 'El campo :attribute debe tener entre :min y :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'email',
            'password' => 'contraseña',
            'name' => 'nombre',
            'role' => 'rol'
        ];
    }
    /**
     *
     * Se sobreescribe éste método para regresara un json con los mensajes de error
     * @Overraide
     *
     */
    protected function failedValidation(Validator $validator)
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            response()->json(['success' => false, 'errors' => $errors], JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
        );
    }
}
