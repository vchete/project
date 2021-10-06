<?php

namespace Hcode\Project\Providers;

use Illuminate\Routing\Router;
use Hcode\Project\Middleware\AppTenant;
use Illuminate\Support\ServiceProvider;

class ProjectAppTenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(\Illuminate\Contracts\Http\Kernel $kernel)
    {
       $kernel->pushMiddleware(AppTenant::class);
    }
}
