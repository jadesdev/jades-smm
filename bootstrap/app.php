<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => \App\Http\Middleware\Admin::class,
            'admin.guest' => \App\Http\Middleware\AdminGuest::class,
            'user' => \App\Http\Middleware\User::class,
        ]);
        $middleware->redirectUsersTo(fn (Request $request) => route('user.dashboard'));
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
