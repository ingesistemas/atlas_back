<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocalidadRequest extends FormRequest
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
        $id = $this->route('localidad'); // ID de la localidad (para edición)
        $datosUsuario = JWTAuth::parseToken()->getPayload();

        $this->merge([
            'id_ciudad' => $datosUsuario->get('id_ciudad')
        ]);

        return [
            'localidad' => [
                'required',
                'string',
                'max:30',
                Rule::unique('localidades', 'localidad')->ignore($id),
            ],
            'p_cardinal' => [
                'required',
                'string',
                'max:30'
            ],
            'id_ciudad' => [
                'required',
                'integer',
                Rule::exists('mysql.ciudades', 'id'),
            ],
        ];
    }

    public function messages()
    {
        $datosUsuario = JWTAuth::parseToken()->getPayload();
        $id_ciudad = $datosUsuario->get('id_ciudad');
        return [
            'localidad.required'   => 'Falta ingresar el nombre de la localidad.',
            'localidad.unique' => 'Esta localidad ya se encuentra registrado en el sistema.',
            'id_ciudad.required'   => 'Falta ingresar la ciudad a la cual pertenece la localidad.',
            'id_ciudad.exists' => $id_ciudad.'La ciudad no se encuentra registrada en el sistema.',
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

