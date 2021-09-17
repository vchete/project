<?php

namespace Hcode\Project\Providers;

use Illuminate\Support\ServiceProvider;
use Hcode\Project\Console\Commands\VueCommand;
use Hcode\Project\Console\Commands\CrudCommand;
use Hcode\Project\Console\Commands\ViewsCommand;
use Hcode\Project\Console\Commands\TenantCommand;
use Hcode\Project\Console\Commands\TemplateCommand;

class ProjectServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //Load Controllers
        $this->app->make('Hcode\Project\Http\Controllers\AppController');
        $this->app->make('Hcode\Project\Http\Controllers\AppCrudController');
        
        //Load Views
        $this->loadViewsFrom(__DIR__.'/../resources/views/crud', 'crud');
        $this->loadViewsFrom(__DIR__.'/../resources/views/errors', 'errors');
        $this->loadViewsFrom(__DIR__.'/../resources/views/backend', 'backend');
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                CrudCommand::class,
                ViewsCommand::class,
                VueCommand::class,
                TenantCommand::class,
                TemplateCommand::class
            ]);
        }
        
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->publishes([
            __DIR__.'/../config' => $this->app->basePath() . '/config',
        ], 'hcode-config');

        // foreach (glob(__DIR__.'/../Helpers/*.php') as $filename) {
        //     require_once $filename;
        // }
    }
}
