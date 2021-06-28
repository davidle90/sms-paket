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

        if(!isset($form_id)) {
            $sourceable->delete();
            return $sourceable;
        }

        $sourceable->form_id            = $form_id;
        $sourceable->sourceable_type    = $type;
        $sourceable->sourceable_id      = $id;
        $sourceable->save();

        return $sourceable;
    }

    public function get_form_via_sourceable($type, $id)
    {
        $form_sourceable = rl_forms::forms_sourceable_model()::where('sourceable_type', $type)
            ->where('sourceable_id', $id)
            ->first();

        if(!isset($form_sourceable)) {
            return null;
        }

        $form = rl_forms::forms_get($form_sourceable->form_id);

        return $form;
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


