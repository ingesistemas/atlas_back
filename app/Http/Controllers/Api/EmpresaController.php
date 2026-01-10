<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Role;
//use Illuminate\Http\Request;
use App\Http\Requests\EmpresaRequest;
use Exception;

class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar()
    {
        $empresas = Empresa::with([
            'ciudad.dpto'
        ])->get();

        return response()->json([
            'error' => false,
            'data' => $empresas
        ]);
    }

    public function crear(EmpresaRequest $request)
    {
        try{
            $data = $request->validated();

            if($data){
                $empresa = Empresa::create([
                    'nit' => $request->nit,
                    'dig_ver'=> $request->dig_ver,
                    'id_ciudad'=> $request->id_ciudad,
                    'nombre'=> $request->nombre,
                    'email'=> $request->email,
                    'web'=> $request->web,
                    'lema'=> $request->lema
                ]);

                return response()->json([
                    'error' => false,
                    'mensaje' => 'El registro fue creado correctamente.',
                    'data' => $empresa
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

    public function editar(EmpresaRequest $request, string $id)
    {
        $empresa = Empresa::find($id);
        if(!$empresa){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar.',
                'data' => []
            ]);
        }else{
            $empresa->nit = $request->nit;
            $empresa->dig_ver =  $request->dig_ver;
            $empresa->id_ciudad = $request->id_ciudad;
            $empresa->nombre = $request->nombre;
            $empresa->email = $request->email;
            $empresa->web = $request->web;
            $empresa->lema = $request->lema;
            $empresa->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado correctamente.',
                'data' => $empresa
            ]);
        }
    }

    public function estado(string $id)
    {
        $empresa = Empresa::find($id);
        if(!$empresa){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar su estado.',
                'data' => []
            ]);
        }else{
            $empresa->activo = $empresa->activo == 1 ? 0 : 1;
            $empresa->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado en su estado correctamente.',
                'data' => $empresa
            ]);
        }
    }
}
