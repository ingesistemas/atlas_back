<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RolRequest;
use App\Models\Role;
use Illuminate\Http\Request;
use Exception;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar()
    {
        return response()->json([
            'error' => false,
            'data' => Role::all()
        ]);
    }

    public function crear(RolRequest $request)
    {
        try{
            $data = $request->validated();
            if($data){
                $role = Role::create([
                    'rol' => $request->rol
                ]);

                return response()->json([
                    'error' => false,
                    'mensaje' => 'El registro fue creado correctamente.',
                    'data' => $role
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

    public function editar(RolRequest $request, string $id)
    {
        try{
            $data = $request->validated();
            if($data){
                $rol = Role::find($id);
                if(!$rol){
                    return response()->json([
                        'error' => true,
                        'mensaje' => 'No se encontró el registro que deseas editar.',
                        'data' => []
                    ]);
                }else{
                    $rol->rol = $request->rol;
                    $rol->save();

                    return response()->json([
                        'error' => false,
                        'mensaje' => 'El registro fue editado correctamente.',
                        'data' => $rol
                    ]);
                }
            }
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
        $rol = Role::find($id);
        if(!$rol){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar su estado.',
                'data' => []
            ]);
        }else{
             $rol->activo = $rol->activo == 1 ? 0 : 1;
            $rol->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado en su estado correctamente.',
                'data' => $rol
            ]);
        }
    }
}
