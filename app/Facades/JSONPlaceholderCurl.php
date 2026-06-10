<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class JsonPlaceholderCurl extends Facade
{
    /**
     * Get the registered name of the component from the Service Container.
     */
    protected static function getFacadeAccessor()
    {
        return 'jsonplaceholder-curl';
    }
}
