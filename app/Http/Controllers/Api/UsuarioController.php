<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\Usuario;
use Illuminate\Http\Request;
use App\Services\EmpresaConnectionService;
use App\Http\Requests\UsuarioRequest;
use App\Models\Ciudad;
use App\Models\Empresa;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use Exception;

class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function consultar(Request $request)
    {
        $usuarios = Usuario::select('id', 'email', 'id_rol', 'activo', 'created_at')
            ->get();;
       
        return response()->json([
            'error' => false,
            'data' => $usuarios
        ]);
    }

    public function ingresar(Request $request)
    {   
        try{
            $nit = $request->nit;
            $email = $request->email;
            $clave = $request->clave;
            EmpresaConnectionService::conectarPorNit($nit);
            $usuario = Usuario::select('id', 'email', 'atlas', 'id_rol')
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
                // 3️⃣ Consultar modelo Empresa en la DB principal
                $empresa = Empresa::where('nit', $nit)->first();

                $nombreCiudad = Ciudad::where('id', $empresa->id_ciudad)
                    ->value('ciudad');
                if(!$empresa){
                    return response()->json([
                        'error' => true,
                        'mensaje' => 'El NIT ingresado no está autorizado para ingresar al sistema.',
                        'data' => []
                    ]);
                }else{
                    // 3️⃣ Crear token JWT
                    $token = JWTAuth::claims([
                        'nit' => $nit,
                        'rol' => $usuario->rol,
                        'id_ciudad' => $empresa->id_ciudad
                    ])->fromUser($usuario);
                    // 4️⃣ Respuesta al frontend
                    $usuario->id_ciudad = $empresa->id_ciudad;
                    $usuario->empresa = $empresa->nombre;
                    $usuario->ciudad = $nombreCiudad;
                    
                    return response()->json([
                        'error' => false,
                        'token'   => $token,
                        'mensaje' => 'Bienvenido a Atlas APS.',
                        'user'    => $usuario,
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

    public function crear(UsuarioRequest $request)
    {
        try{
            $data = $request->validated();
            if($data){
                if (Usuario::where('email', $request->email)->exists()) {
                    return response()->json([
                        'error' => true,
                        'mensaje' => 'El correo electrónico ingresado ya se encuentra registrado.'
                    ], 422);
                }else{
                    $usuario = Usuario::create([
                        'email' => $request->email,
                        'clave' => $request->clave
                    ]);

                    return response()->json([
                        'error' => false,
                        'mensaje' => 'El registro fue creado correctamente.',
                        'data' => $usuario
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

    public function show(string $id)
    {
        //
    }

    public function editar(Request $request, string $id)
    {
        
        $usuario = Usuario::find($id);
        if(!$usuario){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar.',
                'data' => []
            ]);
        }else{
            if (Usuario::where('email', $request->email)
                ->where('id', '!=', $id)
                ->exists()) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'El correo electrónico ingresado ya se encuentra registrado.'
                ], 422);
            }else{
                $usuario->email = $request->email;
                $usuario->clave = $request->clave;
                $usuario->save();

                return response()->json([
                    'error' => false,
                    'mensaje' => 'El registro fue editado correctamente.',
                    'data' => $usuario
                ]);
            }    
        }
    }

    public function estado(Request $request, string $id)
    {
        $usuario = Usuario::find($id);
        if(!$usuario){
            return response()->json([
                'error' => true,
                'mensaje' => 'No se encontró el registro que deseas editar su estado.',
                'data' => []
            ]);
        }else{
            $usuario->activo = $usuario->activo == 1 ? 0 : 1;
            $usuario->save();

            return response()->json([
                'error' => false,
                'mensaje' => 'El registro fue editado en su estado correctamente.',
                'data' => $usuario
            ]);
        }
    }

    public function testDb(Request $request)
    {
         $payload = JWTAuth::parseToken()->getPayload();
        $nit = $payload->get('nit');

        EmpresaConnectionService::conectarPorNit($nit);

        return response()->json([
            'error' => false,
            'data' => Usuario::count()
        ]);
    }
}
