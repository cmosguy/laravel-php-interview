<?php


namespace V1\Providers;


use Illuminate\Foundation\Support\Providers\RouteServiceProvider;

class V1RouteServiceProvider extends RouteServiceProvider
{
    public function map(): void
    {
        $this->app['router']->group(['prefix' => 'api/v1', 'as' => 'api.v1.', 'middleware' => 'api'],
            app_path('Applications/API/V1/Http/routes.php'));
    }
}
