<?php
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->append(HandleCors::class);
        
        // Alias de middleware de ruta
        $middleware->alias([
            'jwt' => \App\Http\Middleware\JwtMiddleware::class,
        ]);

        $middleware->alias([
            'empresa.jwt' => \App\Http\Middleware\EmpresaJwtMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
         // 404 - Recurso no encontrado
        $exceptions->render(function (NotFoundHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Recurso no encontrado.',
                    'data' => []
                ], 404);
            }
        });

        // 422 - Error de validación
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Error de validación.',
                    'data' => $e->errors()
                ], 422);
            }
        });

        // 405 - Método no permitido
        $exceptions->render(function (MethodNotAllowedHttpException $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Método no permitido.',
                    'data' => []
                ], 405);
            }
        });

        // 500 - Error general
        $exceptions->render(function (Throwable $e, Request $request) {
            if ($request->is('api/*')) {
                return response()->json([
                    'error' => true,
                    'mensaje' => 'Ocurrió un error interno en el servidor.',
                    'data' => config('app.debug') ? $e->getMessage() : []
                ], 500);
            }
        });
    
    })->create();
