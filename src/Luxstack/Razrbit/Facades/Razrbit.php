<?php namespace Luxstack\Razrbit\Facades;

use Illuminate\Support\Facades\Facade;

class Razrbit extends Facade {

    /**
     * Name of the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Razrbit';
    }

}