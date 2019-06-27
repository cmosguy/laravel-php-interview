<?php


namespace V1\DAL;


use App\DAL\Contracts\TipRepository;
use Illuminate\Support\ServiceProvider as IlluminateServiceProvider;
use V1\DAL\Concrete\EloquentTip;

class ServiceProvider extends IlluminateServiceProvider
{
    public function register(): void
    {
        $toBind = [
            TipRepository::class => EloquentTip::class
        ];

        foreach ($toBind as $interface => $concrete) {
            $this->app->bind($interface, $concrete);
        }
    }
}
