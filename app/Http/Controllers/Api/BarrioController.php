<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\BarrioRequest;
use App\Http\Requests\RolRequest;
use App\Models\Barrio;
use App\Models\Localidade;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class BarrioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar(Request $request)
    {
        $id_ficha = $request->header('X-Ficha-Tecnica');
        $datosUsuario = JWTAuth::parseToken()->getPayload();
        $id_ciudad = $datosUsuario->get('id_ciudad');
        $datos = Barrio::where('id_ficha_tecnica', $id_ficha)
            ->whereHas('localidad', function ($query) use ($id_ciudad) {
                $query->where('id_ciudad', $id_ciudad);
            })
            ->with(['localidad', 'ficha']);

        if (!empty($id_ficha)) {
            $datos->where('id_ficha_tecnica', $id_ficha);
        } else {
            $datos->where(function ($query) {
                $query->whereNull('id_ficha_tecnica')
                    ->orWhere('id_ficha_tecnica', '');
            });
        }

        return response()->json([
            'error' => false,
            'data' => $datos->get(),
            'mensaje' => 'Datos cargados desde la nube.'
        ]);
    }

    public function crear(BarrioRequest $request)
    {
        try{
            $data = $request->validated();
            if($data){
                $id_ficha = $request->header('X-Ficha-Tecnica');
                $barrio = Barrio::create([
                    'barrio' => $request->barrio,
                    'id_localidad' => $request->id_localidad,
                    'id_ficha_tecnica' => $id_ficha,
                    'alerta' => $request->alerta
                ]);

                return response()->json([
                    'error' => false,
                    'mensaje' => 'El registro fue creado correctamente.',
                    'data' => $barrio
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

    public function editar(BarrioRequest $request,  $id)
    {
        try{
            $barrio = Barrio::findOrFail($id);
            $barrio->update($request->validated());
            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado correctamente.',
                'data' => $barrio
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
        $barrio = Barrio::find($id);
        if(!$barrio){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar su estado.',
                'data' => []
            ]);
        }else{
            $barrio->activo = $barrio->activo == 1 ? 0 : 1;
            $barrio->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado en su estado correctamente.',
                'data' => $barrio
            ]);
        }
    }

}
