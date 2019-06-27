<?php


namespace App\Modules\Response\Json;


use Illuminate\Support\ServiceProvider;

class JsonServiceProvider extends ServiceProvider
{
    /**
     * Bind the json interface to the concrete factory
     */
    public function register(): void
    {
        $this->app->bind(JsonResponseFactory::class, JsonFactory::class);
    }
}
