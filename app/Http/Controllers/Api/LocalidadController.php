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
        $id_ficha = $request->header('X-Ficha-Tecnica');
        $datosUsuario = JWTAuth::parseToken()->getPayload();
        $id_ciudad = $datosUsuario->get('id_ciudad');
        $datos = Localidade::where('id_ciudad', $id_ciudad)
                ->with(['ficha','ciudad']);;

        if (!empty($id_ficha)) {
            $datos->where('id_ficha_tecnica', $id_ficha);
        }else{
            $datos->where(function ($query) {
                $query->whereNull('id_ficha_tecnica')
                    ->orWhere('id_ficha_tecnica', '');
            });
        }
        return response()->json([
            'error' => false,
            'data' => $datos->get()
        ]);
    }

    public function crear(LocalidadRequest $request)
    {
        try{
            $data = $request->validated();
            if($data){
                $id_ficha = $request->header('X-Ficha-Tecnica');
                $localidad = Localidade::create([
                    'localidad' => $request->localidad,
                    'p_cardinal'=> $request->p_cardinal,
                    'id_ciudad' => $request->id_ciudad,
                    'id_ficha_tecnica' => $id_ficha
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

    public function editar(LocalidadRequest $request,  $id)
    {
        try{
            $localidad = Localidade::findOrFail($id);
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

    public function estado(Request $request, string $id)
    {
        $localidad = Localidade::find($id);
        if(!$localidad){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar su estado.',
                'data' => []
            ]);
        }else{
            $localidad->activo = $localidad->activo == 1 ? 0 : 1;
            $localidad->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado en su estado correctamente.',
                'data' => $localidad
            ]);
        }
    }

}
