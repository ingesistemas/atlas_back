<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LocalidadRequest;
use App\Http\Requests\RolRequest;
use App\Models\Localidade;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class LocalidadController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar(Request $request)
    {
        $datosUsuario = JWTAuth::parseToken()->getPayload();
        $id_ciudad = $datosUsuario->get('id_ciudad');
        return response()->json([
            'error' => $id_ciudad,
            'data' => Localidade::where('id_ciudad', $id_ciudad)->get()
        ]);
    }

    public function crear(LocalidadRequest $request)
    {
        try{
            $data = $request->validated();
            if($data){
                $localidad = Localidade::create([
                    'localidad' => $request->localidad,
                    'p_cardinal'=> $request->p_cardinal,
                    'id_ciudad' => $request->id_ciudad
                ]);

                return response()->json([
                    'error' => false,
                    'mensaje' => 'El registro fue creado correctamente.',
                    'data' => $localidad
                ]);
            }
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'mensaje' => 'Error'. $e,
                'data' => []
            ]);
        }

    }

    public function show(string $id)
    {
        //
    }

    public function editar(LocalidadRequest $request, Localidade $localidad)
    {
        try{
            $localidad->update($request->validated());
            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado correctamente.',
                'data' => $localidad
            ]);
        }catch(Exception $e){
            return response()->json([
                'error' => true,
                'mensaje' => 'Error'. $e,
                'data' => []
            ]);
        }        
    }

}
