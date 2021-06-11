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

    public function forms_sections_model()
    {
        return config('rl_forms.models.forms_sections');
    }

    public function forms_responses_model()
    {
        return config('rl_forms.models.forms_responses');
    }

    public function forms_sourceable_model()
    {
        return config('rl_forms.models.forms_sourceable');
    }

    /*
     *  Forms
     */
    public function forms_get($id = null)
    {
        if(isset($id)) {
            return rl_forms::forms_model()::find($id);
        }

        return Forms::get();
    }

    public function forms_sections_get($form_id)
    {
        return rl_forms::forms_sections_model()::where('form_id', $form_id)
            ->with('translations')
            ->with('elements')
            ->get();
    }

    public function forms_sourceable_store($form_id, $type, $id)
    {
        $sourceable = rl_forms::forms_sourceable_model()::firstOrNew([
            'sourceable_type' => $type,
            'sourceable_id'   => $id
        ]);

        $sourceable->form_id            = $form_id;
        $sourceable->sourceable_type    = $type;
        $sourceable->sourceable_id      = $id;
        $sourceable->save();

        return $sourceable;
    }

    /*
     *  Responses
     */
    public function forms_responses_get($form_id, $iso)
    {
        return rl_forms::forms_responses_model()::where([
            ['form_id', '=', $form_id],
            ['iso',     '=', $iso],
        ])->get();
    }

    public function forms_responses_store($form_id, $input)
    {
        //Store responses
    }

}


