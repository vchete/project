<?php

namespace Hcode\Project\Providers;

use Illuminate\Routing\Router;
use App\Http\Middleware\AppTenant;
use Illuminate\Support\ServiceProvider;

class ProjectAppTenantServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(Router::class)->aliasMiddleware('hcTenant', AppTenant::class);
    }
}
