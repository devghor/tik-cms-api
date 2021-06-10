<?php

namespace Tikweb\TikCmsApi\Providers;

use Illuminate\Support\ServiceProvider;

class TikCmsApiServiceProvider  extends ServiceProvider
{

    /**
     * Bootstrap services.
     *
     * @return void
     * @throws \Illuminate\Contracts\Container\BindingResolutionException
     */
    public function boot()
    {
        $this->loadRoutesFrom(__DIR__ . '/../Http/routes.php');
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {


    }

}
