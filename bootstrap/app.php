<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        using:function(){
            $centralDomains =config('tenancy.central_domains'); 
            foreach ($centralDomains as $domain) {
                Route::middleware('web')
                ->domain($domain)
                ->group(base_path('routes/web.php'));
            } 
            //Route::middleware('api')->group(base_path('routes/tenant.php'));
            Route::middleware(['api', 'tenant']) // Asegúrate de incluir el middleware `tenant`
            ->group(base_path('routes/tenant.php')); 

            //Route::middleware('web')->group(base_path('routes/tenant_web.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->group('universal', []);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
