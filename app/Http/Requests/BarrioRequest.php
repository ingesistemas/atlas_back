<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;

class BarrioRequest extends FormRequest
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
            'id_ciudad' => $datosUsuario->get('id_ciudad'),
            'id_ficha_tecnica' => $this->header('X-Ficha-Tecnica')
        ]);

        return [
            'barrio' => [
                'required',
                'string',
                'max:250',
                Rule::unique('empresa_dinamica.barrios', 'barrio')
                ->where(function ($query) {

                    //$query->where('id_ciudad', $this->id_ciudad);

                    if ($this->id_ficha_tecnica) {
                        $query->where('id_ficha_tecnica', $this->id_ficha_tecnica);
                    } else {
                        $query->whereNull('id_ficha_tecnica');
                    }

                })
                ->ignore($id),
            ],
            'alerta' => [
                'required',
                'integer',
            ],
            'id_ficha_tecnica' => [
                'string',
                'nullable'
            ],
            'id_localidad' => [
                'required',
                'integer',
                Rule::exists('empresa_dinamica.localidades', 'id'),
            ],
        ];
    }

    public function messages()
    {
        $datosUsuario = JWTAuth::parseToken()->getPayload();
        $id_ciudad = $datosUsuario->get('id_ciudad');
        return [
            'barrio.required'   => 'Falta ingresar el nombre del barrio.',
            'alerta.required'   => 'Falta señalar el grado de alerta del barrio.',
            'barrio.unique' => 'Este barrio ya se encuentra registrado en el sistema.',
            'id_localidad.required'  => 'Falta señalar la localidad o comuna a la cual pertenece el barrio.',
            'id_localidad.exists' => 'La localidad o comuna no se encuentra registrada en el sistema.',
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

