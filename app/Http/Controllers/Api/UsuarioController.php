<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Services\EmpresaConnectionService;
use Exception;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar(Request $request)
    {
        $nit = $request->nit;
        EmpresaConnectionService::conectarPorNit($nit);
        return response()->json([
            'error' => false,
            'data' => Usuario::all()
        ]);
    }

    public function ingresar(Request $request)
    {   
        $nit = $request->nit;
        $email = $request->email;
        $clave = $request->clave;
        
        EmpresaConnectionService::conectarPorNit($nit);
        $usuario = Usuario::select('id', 'email')
            ->where('email', $email)
            ->where('clave', $clave)
            ->first();

        if(!$usuario){
            return response()->json([
                'error' => true,
                'mensaje' => 'Acceso denegado.',
                'data' => []
            ]);
        }else{
            return response()->json([
                'error' => false,
                'mensaje' => 'Bienvenido a Atlas.',
                'data' => $usuario
            ]);
        }
    }
    public function crear(Request $request)
    {
        try{
            $role = Role::create([
            'rol' => $request->rol
            ]);

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue creado correctamente.',
                'data' => $role
            ]);
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

    public function editar(Request $request, string $id)
    {
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
