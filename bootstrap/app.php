<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // Validar Errores.
        $exceptions->render(function (ValidationException $ex) {
            $errors = $ex->errors();
            $errorArr = [];
            foreach ($errors as $message) {
                $errorArr = array_merge($errorArr,$message);
            }
            return response(["errors" => $errorArr],Response::HTTP_BAD_REQUEST);
        });

    })->create();
