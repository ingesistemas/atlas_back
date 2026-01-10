<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class EmpresaRequest extends FormRequest
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
     */
    public function rules(): array
    {
        $id = $this->route('id'); // ID de la empresa (para edición)

        return [
            'nit' => 'required|string|max:30|unique:empresas,nit,' . $id,
            'dig_ver'=> 'required|string|max:2',
            'id_ciudad' => 'required|int',
            'nombre' => 'required|string|max:100'
        ];
    }

    public function messages()
    {
        return [
            'nit.required'   => 'Falta ingresar el número del nit.',
            'nit.unique'     => 'El NIT ya se encuentra registrado.',
            'dig_ver.required' => 'Falta ingresar el dígito de verificación.',
            'dig_ver.max' => 'Se acepta máximo 2 caracteres para el dígito de verificación.',
            'id_ciudad.required'  => 'Falta señalar la ciudad.',
            'nombre.required'     => 'Falta ingresar el nombre de la empresa.',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'error' => true,
                'mensaje' => 'Error de validación',
                'errores' => $validator->errors()
            ], 422)
        );
    }
}

