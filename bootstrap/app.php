<?php

use App\Http\Middleware\LoginMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

require_once __DIR__ . '/../app/Helpers/MyHelper.php';
return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'login' => \App\Http\Middleware\LoginMiddleware::class,
            'admin' =>  \App\Http\Middleware\AuthenticatedMiddleware::class,
            'locale' =>  \App\Http\Middleware\SetLocale::class,
            'default_locale' => \App\Http\Middleware\SetDefaultLocale::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
