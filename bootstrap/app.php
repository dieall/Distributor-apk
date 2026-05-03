<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);

        // Hindari redirect loop: middleware `guest` default mengarahkan ke `/` jika tidak ada
        // route bernama `dashboard`/`home`, sementara `/` di web.php mengarah ke login.
        $middleware->redirectUsersTo(
            fn (Request $request) => route($request->user()->getDashboardRoute())
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
