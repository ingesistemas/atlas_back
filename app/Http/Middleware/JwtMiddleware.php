<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Services\EmpresaConnectionService;

class JwtMiddleware
{
    public function handle($request, Closure $next)
    {
        try {
            // 1️⃣ Verificar token
            $user = JWTAuth::parseToken()->authenticate();

            // 2️⃣ Obtener payload completo
            $payload = JWTAuth::parseToken()->getPayload();

            // 3️⃣ Extraer NIT del token
            $nit = $payload->get('nit');

            // 4️⃣ Guardar NIT en el contenedor
            app()->instance('nit', $nit);

            // 🔥 CONEXIÓN DINÁMICA REAL
            EmpresaConnectionService::conectarPorNit($nit);

        } catch (JWTException $e) {
            return response()->json([
                'error' => true,
                'mensaje' => 'Token inválido o expirado'
            ], 401);
        }

        return $next($request);
    }
}

