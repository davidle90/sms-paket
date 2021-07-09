<?php namespace Rocketlabs\Forms\App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Collection as BaseCollection;

/*
 * Helpers
 */

use Illuminate\Support\Facades\Config;
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

    public function forms_elements_model()
    {
        return config('rl_forms.models.forms_elements');
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

    public function forms_rules_messages_get($form_data, $form_id)
    {
        $rules      = [];
        $messages   = [];

        $form = rl_forms::forms_get($form_id);

        //pre($form->toArray());

        foreach ($form->sections as $section_index => $section) {
            foreach ($section->elements as $element_index => $element) {
                $table_validaton_str = '';

                if(isset($element->table) || (isset($element->options) && !$element->options->isEmpty())) {

                    $table_validaton_str = '|in:';

                    if(isset($element->table)) {
                        foreach ($element->table->data as $table_data) {
                            foreach ($table_data->translations as $value) {
                                $table_validaton_str .= $value->translation.',';
                            }
                        }
                    }

                    if(isset($element->options) && !$element->options->isEmpty()) {
                        foreach ($element->options as $option) {
                            foreach ($option->translations as $value) {
                                $table_validaton_str .= $value->translation.',';
                            }
                        }
                    }

                    $messages['form.'.$section_index.'.'.$element_index.'.value.in'] = 'Detta fält är ett krav.';
                }


                if($element->pivot->required == 1){
                    $rules['form.'.$section_index.'.'.$element_index.'.value']              = 'required|';
                    $messages['form.'.$section_index.'.'.$element_index.'.value.required']  = 'Detta fält är ett krav.';

                    if($element->type_id == 3 || $element->type_id == 4) {
                        $rules['form.'.$section_index.'.'.$element_index.'.value.*'] = ''.$table_validaton_str;
                    } else {
                        $rules['form.'.$section_index.'.'.$element_index.'.value'] .= $element->validator.$table_validaton_str;
                    }

                    if(isset($element->in('sv')->required) && !empty($element->in('sv')->required) && !empty($element->validator)) {
                        $valdation_rules = explode('|' ,$element->validator);

                        foreach ($valdation_rules as $valdation_rule) {
                            $rule = explode(':', $valdation_rule)[0] ?? '';
                            $messages['form.'.$section_index.'.'.$element_index.'.value.'.$rule] = $element->in('sv')->required;
                        }
                    }
                } elseif(!empty($form_data[$section_index][$element_index]['value']) && !empty($element->validator)) {
                    if($element->type_id == 3 || $element->type_id == 4) {
                        $rules['form.'.$section_index.'.'.$element_index.'.value.*'] = ''.$table_validaton_str;
                    } else {
                        $rules['form.'.$section_index.'.'.$element_index.'.value'] = $element->validator.$table_validaton_str;
                    }

                    if(isset($element->in('sv')->required) && !empty($element->in('sv')->required)) {
                        $valdation_rules = explode('|' ,$element->validator);

                        foreach ($valdation_rules as $valdation_rule) {
                            $rule = explode(':', $valdation_rule)[0] ?? '';
                            $messages['form.'.$section_index.'.'.$element_index.'.value.'.$rule] = $element->in('sv')->required;
                        }
                    }
                } elseif(!empty($form_data[$section_index][$element_index]['value']) && !empty($table_validaton_str)) {
                    if($element->type_id == 3 || $element->type_id == 4) {
                        $rules['form.'.$section_index.'.'.$element_index.'.value.*'] = ''.$table_validaton_str;
                    } else {
                        $rules['form.'.$section_index.'.'.$element_index.'.value'] = ''.$table_validaton_str;
                    }
                }
            }
        }

        return [
            'rules'     => $rules,
            'messages'  => $messages,
        ];
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

    public function forms_get_formatted_response($sourceable_type, $sourceable_id, $iso, $compressed = true)
    {
        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');
        $formatted_response = [];

        $response = rl_forms::forms_responses_model()::with(['data.sourceable', 'data.element.sections'])->where([
            ['sourceable_type', '=', $sourceable_type],
            ['sourceable_id',   '=', $sourceable_id],
            ['iso',             '=', $iso],
        ])->first();

        if(!isset($response->form_id)) {
            return $formatted_response;
        }

        $form = rl_forms::forms_model()::with([
            'sections.elements.data' => function($query) use($response) {
                $query->versionData([$response->id]);
            },
            'sections.elements.data.sourceable'
        ])->find($response->form_id);

        if(!isset($form)) {
            return $formatted_response;
        }

        foreach ($form->sections as $section) {

            if($compressed == true){

                foreach ($section->elements as $element) {
                    $formatted_response[$element->slug]['label']    = $element->in($iso ?? $default_language ?? $fallback_language ?? 'sv')->label ?? '';
                    $formatted_response[$element->slug]['type_id']  =  $element->type_id;

                    if($element->type_id === 3 || $element->type_id === 4) {
                        $formatted_response[$element->slug]['value'] = [];

                        foreach ($element->data as $data) {
                            $formatted_response[$element->slug]['value'][] = $data->sourceable->value ?? '';
                        }
                    } else {
                        $formatted_response[$element->slug]['value'] = $element->data->first()->sourceable->value ?? '';
                    }
                }


            } else {

                $formatted_response[$section->slug]['label'] = $section->in($iso ?? $default_language ?? $fallback_language ?? 'sv')->label ?? '';

                foreach ($section->elements as $element) {
                    $formatted_response[$section->slug]['elements'][$element->slug]['label']    = $element->in($iso ?? $default_language ?? $fallback_language ?? 'sv')->label ?? '';
                    $formatted_response[$section->slug]['elements'][$element->slug]['type_id']  =  $element->type_id;

                    if($element->type_id === 3 || $element->type_id === 4) {
                        $formatted_response[$section->slug]['elements'][$element->slug]['value'] = [];

                        foreach ($element->data as $data) {
                            $formatted_response[$section->slug]['elements'][$element->slug]['value'][] = $data->sourceable->value ?? '';
                        }
                    } else {
                        $formatted_response[$section->slug]['elements'][$element->slug]['value'] = $element->data->first()->sourceable->value ?? '';
                    }
                }

            }

        }

        /*
         * Return formatted response
         */
        return $formatted_response;
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

    public function forms_response_drop($sourceable_type, $sourceable_id)
    {
        $responses_to_delete = rl_forms::forms_responses_model()::with(['data.sourceable'])->where([
            ['sourceable_type', '=', $sourceable_type],
            ['sourceable_id',   '=', $sourceable_id],
        ])->get();

        foreach ($responses_to_delete as $response) {
            $response_data_to_delete = \rl_forms::forms_responses_data_model()::where('response_id', $response->id)
                ->get();

            foreach ($response_data_to_delete as $data) {
                $data->sourceable()->delete();
                $data->delete();
            }

            $response->delete();
        }

    }

}


