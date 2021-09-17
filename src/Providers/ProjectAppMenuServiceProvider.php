<?php

namespace Hcode\Project\Providers;

use Illuminate\Routing\Router;
use Hcode\Project\Middleware\AppMenu;
use Illuminate\Support\ServiceProvider;

class ProjectAppMenuServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $router = $this->app->make(Router::class);
        $this->app->make(Router::class)->aliasMiddleware('hcMenu', AppMenu::class);
    }
}
