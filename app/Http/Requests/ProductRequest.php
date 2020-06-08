<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class ProductRequest extends FormRequest
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
            'name' => 'required|string|between:3,70',
            'description' => 'required|between:2,255',
            'unit_price' => 'required|numeric',
            'purchase_price' => 'required|numeric',
            'department' => 'required|numeric',
            'category' => 'required|numeric',
            'brand' => 'required|numeric',
            'quantity_small_size' => 'required|numeric',
            'quantity_medium_size' => 'required|numeric',
            'quantity_big_size' => 'required|numeric',
        ];
    }


    public function messages()
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'numeric' => 'El campo :attribute debe ser númerico.',
            'name.string' => 'El nombre solo debe contener letras (string).',
            'between' => 'El campo :attribute debe tener entre :min y :max caracteres.',
        ];
    }

    public function attributes()
    {
        return [
            'email' => 'email',
            'description' => 'descripción',
            'name' => 'nombre',
            'department' => 'departamento',
            'category' => 'categoria',
            'brand' => 'marca',
            'quantity_small_size' => 'cantidad talla chica',
            'quantity_medium_size' => 'cantidad talla mediana',
            'quantity_big_size' => 'cantidad talla grande'
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
