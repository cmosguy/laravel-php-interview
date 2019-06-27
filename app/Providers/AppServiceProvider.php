<?php

namespace App\Providers;

use App\Modules\ServiceProvider as ModulesServiceProvider;
use Illuminate\Support\ServiceProvider;
use V1\Providers\V1ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     * @return void
     */
    public function register()
    {
        $this->app->register(ModulesServiceProvider::class);
        $this->app->register(V1ServiceProvider::class);
    }
}
