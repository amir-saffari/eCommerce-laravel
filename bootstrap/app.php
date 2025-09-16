<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',

        then: function ()
        {
            Route::prefix('api/v1/client')
                ->middleware('api')
                ->group(base_path('routes/Api/V1/Client/auth.php'));

            Route::prefix('api/v1/client')
                ->middleware('api' , 'auth:api')
                ->group(base_path('routes/Api/V1/Client/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        $exceptions->renderable(function (AuthenticationException $e) {
            return response()->json([
                'message' => 'Unauthenticated.',
                'status' => 401,
            ], 401);
        });

        $exceptions->renderable(function (AuthorizationException $e) {
            return response()->json([
                'message' => 'This action is unauthorized.',
                'status' => 403,
            ], 403);
        });

        $exceptions->renderable(function (ValidationException $e) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'status' => 422,
                'errors' => $e->errors(),
            ], 422);
        });

        $exceptions->renderable(function (NotFoundHttpException $e) {
            return response()->json([
                'message' => 'Resource not found.',
                'status' => 404,
            ], 404);
        });

        $exceptions->renderable(function (MethodNotAllowedHttpException $e) {
            return response()->json([
                'message' => 'Method not allowed.',
                'status' => 405,
            ], 405);
        });

        $exceptions->renderable(function (HttpException $e) {
            return response()->json([
                'message' => $e->getMessage(),
                'status' => $e->getStatusCode(),
            ], $e->getStatusCode());
        });

        $exceptions->renderable(function (QueryException $e) {

            $message = app()->isProduction() ? 'Internal server error.' : $e->getMessage();

            return response()->json([
                'message' => $message,
                'status' => 500,
            ], 500);
        });

        $exceptions->render(function (Throwable $e, Request $request) {

            logger()->error('API Exception: ' . $e->getMessage(), [
                'exception' => get_class($e),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => $request->user()?->id,
            ]);

            $message = app()->environment('production')
                ? 'Server error occurred.'
                : $e->getMessage();

            return response()->json([
                'message' => $message,
            ], 500);
        });

        $exceptions->report(function (Throwable $e) {
            if ($e instanceof QueryException || $e->getCode() >= 500) {
                logger()->critical('Critical application error', [
                    'exception' => get_class($e),
                    'message' => $e->getMessage(),
                    'file' => $e->getFile(),
                    'line' => $e->getLine(),
                ]);
            }
        });

        $exceptions->dontReport([
            AuthenticationException::class,
            AuthorizationException::class,
            ValidationException::class,
            ModelNotFoundException::class,
            NotFoundHttpException::class,
        ]);
    })->create();
