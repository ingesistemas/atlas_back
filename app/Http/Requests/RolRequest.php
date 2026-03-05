<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class RolRequest extends FormRequest
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
            'rol' => 'required|string|max:30' . $id,
            'rol' => 'required|string|max:30|unique:roles,rol,' . $id,
        ];
    }

    public function messages()
    {
        return [
            'rol.required'   => 'Falta ingresar el nombre del Rol.',
            'rol.unique' => 'Este Rol ya se encuentra registrado en el sistema.',
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

