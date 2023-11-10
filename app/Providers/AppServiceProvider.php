<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if ($this->app->isLocal()) {
            //if local register your services you require for development
            $this->app['request']->server->set('HTTP', true);
        } else {
            //else register your services you require for production
            $this->app['request']->server->set('HTTPS', true);
        }
    }
}
