<?php

use Illuminate\Console\Scheduling\Schedule;
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
    ->withSchedule(function (Schedule $schedule): void {
        $schedule->command('pengeluaran:post-aset-cicilan')->dailyAt('06:00');
    })
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
        $middleware->appendToGroup('web', [
            \App\Http\Middleware\SecurityHeaders::class,
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
