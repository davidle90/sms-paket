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

    public function forms_responses_data_model()
    {
        return config('rl_forms.models.forms_responses_data');
    }

    public function forms_responses_data_single_model()
    {
        return config('rl_forms.models.forms_responses_data_single');
    }

    public function forms_responses_data_text_model()
    {
        return config('rl_forms.models.forms_responses_data_text');
    }

    public function forms_responses_data_multiple_model()
    {
        return config('rl_forms.models.forms_responses_data_multiple');
    }

    public function forms_sourceable_model()
    {
        return config('rl_forms.models.forms_sourceable');
    }

    /*
     *  Forms
     */
    public function forms_get($id = null, $response_ids = null)
    {
        $forms_query = rl_forms::forms_model()::query();

        $forms_query->with([
            'sections.elements.options',
            'sections.elements.table.data',
        ]);

        if(!empty($response_ids)) {
            $forms_query->with([
                'sections.elements.data' => function($query) use($response_ids) {
                    $query->versionData($response_ids);
                },
                'sections.elements.data.sourceable'
            ]);
        }

        if(isset($id)) {
            return $forms_query->find($id);
        }

        return $forms_query->get();
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

    public function get_form_via_sourceable($type, $id, $response_ids = null)
    {
        $form_sourceable = rl_forms::forms_sourceable_model()::where('sourceable_type', $type)
            ->where('sourceable_id', $id)
            ->first();

        if(!isset($form_sourceable)) {
            return null;
        }

        $form = rl_forms::forms_get($form_sourceable->form_id, $response_ids);

        return $form;
    }

    /*
     *  Responses
     */
    public function forms_response_get($sourceable_type, $sourceable_id, $iso)
    {
        return rl_forms::forms_responses_model()::with(['data.sourceable'])->where([
            ['sourceable_type', '=', $sourceable_type],
            ['sourceable_id',   '=', $sourceable_id],
            ['iso',             '=', $iso],
        ])->get();
    }

    public function forms_responses_store($sourceable_type, $sourceable_id, $form_id, $iso, $input)
    {
        if(isset($input) && !empty($input) && isset($form_id)) {

            //Creating new response in forms_responses table.
            $form_response = \rl_forms::forms_responses_model()::firstOrNew([
                'sourceable_type'    => $sourceable_type,
                'sourceable_id'      => $sourceable_id
            ]);

            $form_response->sourceable_type = $sourceable_type;
            $form_response->sourceable_id   = $sourceable_id;
            $form_response->form_id         = $form_id;
            $form_response->iso             = $iso;
            $form_response->save();

            $response_data_ids = [];

            foreach ($input as $section) {
                foreach ($section as $element) {
                    if (!empty($element['value'])) {
                        switch ($element['type_id']) {
                            /*
                             * Single
                             */
                            case ($element['type_id'] == 1 || $element['type_id'] == 2 || $element['type_id'] == 5):

                                //Storing value in forms_responses_data_single table.
                                $single_data = rl_forms::forms_responses_data_single_model()::create([
                                    'table_data_id' => null,
                                    'value' => $element['value'],
                                ]);

                                //Creating new data in forms_responses_data table, with morph from forms_responses_data_single.
                                $response_data = rl_forms::forms_responses_data_model()::create([
                                    'slug' => $element['slug'],
                                    'response_id' => $form_response->id,
                                    'element_id' => $element['id'],
                                    'sourceable_type' => 'Rocketlabs\Forms\App\Models\Responses\Data\Single',
                                    'sourceable_id' => $single_data->id
                                ]);

                                $response_data_ids[] = $response_data->id;

                                break;
                            /*
                             * Multiple
                             */
                            case ($element['type_id'] == 3 || $element['type_id'] == 4):
                                foreach ($element['value'] as $value) {

                                    //Storing value in forms_responses_data_multiple table.
                                    $multiple_data = rl_forms::forms_responses_data_multiple_model()::create([
                                        'table_data_id' => null,
                                        'value' => $value,
                                    ]);

                                    //Creating new data in forms_responses_data table, with morph from forms_responses_data_multiple.
                                    $response_data = rl_forms::forms_responses_data_model()::create([
                                        'slug' => $element['slug'],
                                        'response_id' => $form_response->id,
                                        'element_id' => $element['id'],
                                        'sourceable_type' => 'Rocketlabs\Forms\App\Models\Responses\Data\Multiple',
                                        'sourceable_id' => $multiple_data->id
                                    ]);

                                    $response_data_ids[] = $response_data->id;

                                }

                                break;
                            /*
                            * Text
                            */
                            case ($element['type_id'] == 6):

                                //Storing value in forms_responses_data_text table.
                                $text_data = rl_forms::forms_responses_data_text_model()::create([
                                    'table_data_id' => null,
                                    'value' => $element['value'],
                                ]);

                                //Creating new data in forms_responses_data table, with morph from forms_responses_data_text.
                                $response_data = rl_forms::forms_responses_data_model()::create([
                                    'slug' => $element['slug'],
                                    'response_id' => $form_response->id,
                                    'element_id' => $element['id'],
                                    'sourceable_type' => 'Rocketlabs\Forms\App\Models\Responses\Data\Text',
                                    'sourceable_id' => $text_data->id
                                ]);

                                $response_data_ids[] = $response_data->id;

                                break;
                        }
                    }
                }
            }

            //Deleting duplicate response_data and sourceable data
            $response_data_to_delete = \rl_forms::forms_responses_data_model()::where('response_id', $form_response->id)
                ->whereNotIn('id', $response_data_ids)
                ->get();

            foreach ($response_data_to_delete as $data) {
                $data->sourceable()->delete();
                $data->delete();
            }

        }
    }

}


