<?php

use App\Http\Middleware\RoleMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->renderable(function(AuthenticationException $e, $request){
            return response()->json([
                'error' => 'Unauthorized',
                'message' => 'You need to log in to continue',
            ], 401)->header('X-Error-Type', 'auth');
        });

        $exceptions->renderable(function(AccessDeniedHttpException $e, $request){
            return response()->json([
                'error' => 'Forbidden',
                'message' => 'You do not have permission to do that',
            ], 403)->header('X-Error-Type', 'auth');
        });

        $exceptions->renderable(function(NotFoundHttpException $e, $request){
            return response()->json([
                'error' => 'Not Found',
                'message' => 'This resource does not exist',
            ], 404)->header('X-Error-Type', 'auth');
        });
    })->create();
