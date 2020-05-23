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
            'id_department' => 'required|numeric',
            'id_category' => 'required|numeric',
            'id_brand' => 'required|numeric',
            'quantity_small_price' => 'required|numeric',
            'quantity_medium_price' => 'required|numeric',
            'quantity_big_price' => 'required|numeric',
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
            'id_department' => 'departamento',
            'id_category' => 'categoria',
            'id_brand' => 'marca',
            'quantity_small_price' => 'cantidad talla chica',
            'quantity_medium_price' => 'cantidad talla mediana',
            'quantity_big_price' => 'cantidad talla grande'
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
