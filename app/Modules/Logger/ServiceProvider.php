<?php


namespace App\Modules\Logger;


use App\Modules\Logger\Concrete\FileLogger;
use App\Modules\Logger\Contracts\ClientLogger;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $this->app->bind(ClientLogger::class, FileLogger::class);
    }
}
