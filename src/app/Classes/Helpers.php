<?php namespace Rocketlabs\Forms\App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Collection as BaseCollection;

/*
 * Helpers
 */
use rl_forms;
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
        return config('rl_forms.models.forms');
    }


}


