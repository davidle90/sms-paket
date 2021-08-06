<?php namespace Rocketlabs\Sms\App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Collection as BaseCollection;

/*
 * Helpers
 */

use Illuminate\Support\Facades\Config;
use Lang;
use DB;


class Helpers
{

    public function __construct()
    {

    }

    /*
     * Forms models
     */
    public function forms_model()
    {
        return config('rl_sms.models.sms');
    }

}


