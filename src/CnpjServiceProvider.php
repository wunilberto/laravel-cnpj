<?php
namespace WilliamNovak\Cnpj;

use Illuminate\Support\ServiceProvider;

class CnpjServiceProvider extends ServiceProvider {

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     */
    public function boot()
    {
        //
    }

    /**
     * Register the service provider.
     */
    public function register()
    {
         $this->app->singleton('Cnpj', function(){
            return new \william-novak\laravel-cnpj\Cnpj;
        });
    }

}
