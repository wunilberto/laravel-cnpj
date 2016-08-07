<?php namespace Wmnk\Cnpj\Facades;

use Illuminate\Support\Facades\Facade;

class Cnpj extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'cnpj';
    }

}
