<?php

namespace App\Http\Middleware;

use Closure;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Services\EmpresaConnectionService;
use Symfony\Component\HttpFoundation\Response;

class EmpresaJwtMiddleware
{
    public function handle($request, Closure $next): Response
    {
        try {
            $payload = JWTAuth::parseToken()->getPayload();
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => 'Token inválido o no enviado'
            ], 401);
        }

        $nit = $payload->get('nit');

        if (!$nit) {
            return response()->json([
                'error' => true,
                'message' => 'NIT no presente en el token'
            ], 401);
        }

        EmpresaConnectionService::conectarPorNit($nit);

        return $next($request);
    }
}

