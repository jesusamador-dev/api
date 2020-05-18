<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class LoginUserRequest extends FormRequest
{
    /**
     *
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
            'email' => 'required|email|max:60',
            'password' => 'required'
        ];
    }

    public function messages()
    {
        return [
            'email.required' => 'El email es obligatorio',
            'email.max' => 'Número de caracteres excedidos, máximo 60',
            'email.email' => 'No es un formato correcto.',
            'password.required'  => 'La contraseña es obligatoria',
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
