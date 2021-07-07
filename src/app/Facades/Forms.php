<?php namespace Rocketlabs\Forms\App\Facades;

use Illuminate\Support\Facades\Facade;

class Forms extends Facade {
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Rocketlabs\Forms\App\Classes\Helpers'; // the IoC binding.
    }
}
