<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class CategorieRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
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
            'name' => 'required|string|between:2,20',
            'status' => 'required',
            'department' => 'required'
        ];
    }


    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'between' => 'El campo :attribute debe tener entre :min y :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'name' => 'nombre',
            'status' => 'estado'
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
