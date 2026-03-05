<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;

class UsuarioRequest extends FormRequest
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
            'clave' => 'required|string|max:30',
            'email' => 'required|email',
        ];
    }

    public function messages()
    {
        return [
            'clave.required'   => 'Falta ingresar la contraseña de acceso.',
            'email.required' => 'Falta digitar el correo electrónico.',
            'email.email'    => 'Ingrese un correo electrónico válido.',
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

