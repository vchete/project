<?php

namespace Hcode\Project\Providers;

use Illuminate\Routing\Router;
use Hcode\Project\Middleware\AppAccess;
use Illuminate\Support\ServiceProvider;

class ProjectAppAccessServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->app->make(Router::class)->aliasMiddleware('hcAccess', AppAccess::class);
    }
}
