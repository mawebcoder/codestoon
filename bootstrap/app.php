<?php

declare(strict_types=1);

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: base_path('routes/web.php'),
        health: '/up',
        then: function (Application $app) {
            $files = glob(base_path('routes/api/*.php'));

            foreach ($files as $file) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group($file);
            }
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {})
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
