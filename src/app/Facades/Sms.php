<?php namespace Rocketlabs\Sms\App\Facades;

use Illuminate\Support\Facades\Facade;

class Sms extends Facade {
    /**
     * Get the binding in the IoC container
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'Rocketlabs\Sms\App\Classes\Helpers'; // the IoC binding.
    }
}
