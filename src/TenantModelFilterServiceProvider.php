<?php

namespace Vdrnoyan\TenantModelFilter;


use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Vdrnoyan\TenantModelFilter\Contracts\DomainMakerInterface;
use Vdrnoyan\TenantModelFilter\Contracts\PerDomainFilterableInterface;

class TenantModelFilterServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $this->loadFactoriesFrom(__DIR__.'/../factory');
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/../config/config.php' => config_path('tenant-model-filter.php'),
            ], 'config');

            $this->publishes([
                __DIR__.'/../database/migrations/' => database_path('migrations')
            ], 'migrations');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'tenant-model-filter');

        $this->app->singleton('currentDomain', function () {
            return app()->make(DomainMakerInterface::class)->getCurrent();
        });

        $this->app->bind(DomainMakerInterface::class, function () {
            return new \Vdrnoyan\TenantModelFilter\DomainMaker;
        });

        Collection::macro('onlyForThisDomain', function () {
            return $this->filter(function ($value) {
                return $value->domains()->find(currentDomain()->id) !== null;
            });
        });
    }
}
