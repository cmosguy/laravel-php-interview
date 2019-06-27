<?php


namespace V1\Providers;


use Illuminate\Support\ServiceProvider;
use V1\DAL\ServiceProvider as DalServiceProvider;

class V1ServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->register(V1RouteServiceProvider::class);
        $this->app->register(DalServiceProvider::class);
    }
}
