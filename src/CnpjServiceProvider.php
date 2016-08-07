<?php namespace Wmnk\Cnpj;

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
        $this->app['cnpj'] = $this->app->share(function ($app)
        {
            return new Cnpj($app['request']->server->all());
        });
    }

}
