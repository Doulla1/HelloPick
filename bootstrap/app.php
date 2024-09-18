<?php

use App\Http\Middleware\EnsureIsAdmin;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php', // Adding the api route file
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->api (append: [
            EnsureIsAdmin::class, // Checks if the user is an admin
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
