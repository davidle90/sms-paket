<?php namespace Rocketlabs\Forms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Rocketlabs\Forms\App\Models\Forms;
use Rocketlabs\Forms\App\Models\Forms\Elements\Types;

use DB;
use Rocketlabs\Languages\App\Models\Languages;
use Validator;
use Carbon\Carbon;

class FormsController extends Controller
{

	public function index()
	{

        $forms              = Forms::get();
        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');

        return view('rl_forms::admin.pages.forms.index', [
            'forms'             => $forms,
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language
        ]);

	}

	public function create()
    {

        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');
        $languages          = Languages::all()->keyBy('iso_name');
        $types              = Types::orderBy('sort_order', 'asc')->get();
        $tables             = \rl_tables::tables_model()::get();

        return view('rl_forms::admin.pages.forms.edit', [
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language,
            'languages'         => $languages,
            'types'             => $types,
            'tables'            => $tables
        ]);

    }

	public function edit($id)
    {

        $form               = Forms::with(['sections.elements.type', 'sections.elements.options', 'sections.elements.table.data'])->find($id);
        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');
        $languages          = Languages::all()->keyBy('iso_name');
        $types              = Types::orderBy('sort_order', 'asc')->get();
        $tables             = \rl_tables::tables_model()::get();

        return view('rl_forms::admin.pages.forms.edit', [
            'form'              => $form,
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language,
            'languages'         => $languages,
            'types'             => $types,
            'tables'            => $tables
        ]);

    }

	public function view($id)
    {

        $form               = Forms::with(['sections.elements.options', 'sections.elements.table.data'])->find($id);
        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');

        return view('rl_forms::admin.pages.forms.view', [
            'form'              => $form,
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language,
        ]);

    }

    public function get_element_template()
    {
        $languages          = Languages::all()->keyBy('iso_name');
        $default_language   = Config::get('app.locale');
        $tables             = \rl_tables::tables_model()::get();

        return view('rl_forms::admin.pages.forms.templates.element', [
            'type_id'           => request()->get('type_id', null),
            'type_label'        => request()->get('type_label', null),
            'section_index'     => request()->get('section_index', null),
            'element_index'     => request()->get('element_index', null),
            'sort_order'        => request()->get('sort_order', null),
            'languages'         => $languages,
            'default_language'  => $default_language,
            'tables'            => $tables,
            'template'          => true,
        ]);

    }

    public function get_element_card_template()
    {
        $section_index      = request()->get('section_index', null);
        $element_index      = request()->get('element_index', null);
        $label              = request()->get('label', null);
        $description        = request()->get('description', null);
        $required_text      = request()->get('required_text', null);
        $required           = request()->get('required', null);
        $type_id            = request()->get('type_id', null);
        $table_id           = request()->get('table_id', null);
        $options            = request()->get('options', []);
        $alignment          = request()->get('alignment', null);
        $default_language   = Config::get('app.locale');
        $table              = \rl_tables::tables_model()::where('id', $table_id)->with('data')->first();

        return view('rl_forms::admin.pages.forms.templates.card', [
            'section_index'             => $section_index,
            'element_index'             => $element_index,
            'element_label'             => $label,
            'element_description'       => $description,
            'element_required_text'     => $required_text,
            'element_required'          => $required,
            'element_alignment'         => $alignment,
            'type_id'                   => $type_id,
            'default_language'          => $default_language,
            'table'                     => $table,
            'options'                   => $options,
        ]);

    }

    public function get_section_modal_template()
    {
        $languages          = Languages::all()->keyBy('iso_name');
        $default_language   = Config::get('app.locale');

        return view('rl_forms::admin.pages.forms.modals.section', [
            'section_index'     => request()->get('section_index', null),
            'languages'         => $languages,
            'default_language'  => $default_language,
        ]);

    }

    public function store()
    {
        $input = [
            'id'        => request()->get('form_id', null),
            'labels'    => request()->get('labels', []),
            'slug'      => request()->get('slug'),
            'sections'  => request()->get('sections', null)
        ];

        $rules = [
            'labels.*'  => 'required|max:255',
            'slug'      => 'required|max:255'
        ];

        $validator = Validator::make($input, $rules);

        if($validator->fails()) {

            $var                        = array();
            $var['status']              = 0;
            $var['errors']              = $validator->errors()->toArray();
            $var['message']['title']    = 'Form validation error!';
            $var['message']['text']     = 'Some form value is missing or not properly filled out, please check your input and try again';

            return response()->json($var);

        } else {
            /*
             * Begin database transaction and start inserting into database
             */

            DB::beginTransaction();

            try {

                $form       = Forms::firstOrNew(['id' => $input['id']]);
                $form->slug = $input['slug'];
                $form->save();

                /*
                 * Setting translation
                 */
                foreach($input['labels'] as $key => $translate){
                    $form->setTranslation($key, ['label' => $translate]);
                }

                $form->save();

                $section_ids = [];

                if(isset($input['sections']) && !empty($input['sections'])) {

                    foreach($input['sections'] as $section) {
                        $new_section                = Forms\Sections::firstOrNew(['id' => $section['id']]);
                        $new_section->form_id       = $form->id;
                        $new_section->sort_order    = $section['sort_order'];
                        $new_section->slug          = $section['slug'];
                        $new_section->save();

                        /*
                         * Setting translations
                         */
                        foreach($section['labels'] as $key => $translate){
                            $new_section->setTranslation($key, ['label' => $translate]);
                        }

                        foreach($section['descriptions'] as $key => $translate){
                            $new_section->setTranslation($key, ['description' => $translate]);
                        }

                        $new_section->save();

                        $section_ids[] = $new_section->id;

                        $pivot_data = [];

                        if(isset($section['elements']) && !empty($section['elements'])) {

                            foreach($section['elements'] as $element) {
                                $new_element            = Forms\Elements::firstOrNew(['id' => $element['id']]);
                                $new_element->slug      = $element['slug'];
                                $new_element->type_id   = $element['type_id'];
                                $new_element->validator = $element['validator'];
                                $new_element->table_id  = $element['table'] ?? null;
                                $new_element->alignment = $element['alignment'] ?? null;

                                $new_element->save();

                                /*
                                 * Setting translations
                                 */
                                foreach($element['labels'] as $key => $translate){
                                    $new_element->setTranslation($key, ['label' => $translate]);
                                }

                                foreach($element['descriptions'] as $key => $translate){
                                    $new_element->setTranslation($key, ['description' => $translate]);
                                }

                                foreach($element['required_texts'] as $key => $translate){
                                    $new_element->setTranslation($key, ['required' => $translate]);
                                }

                                $new_element->save();

                                $size_class = '';
                                $size_class = (isset($element['size']['xs'])) ? 'col-'.$element['size']['xs'].' ' : 'col-12 ';

                                if(isset($element['size']) && !empty($element['size'])) {
                                    foreach($element['size'] as $key => $value) {
                                        if($key === 'xs') continue;

                                        if(isset($value)) {
                                            $size_class = $size_class.'col-'.$key.'-'.$value.' ';
                                        }
                                    }
                                }

                                $pivot_data[$new_element->id] = [
                                    'required'   => $element['required'] ?? 0,
                                    'sort_order' => $element['sort_order'],
                                    'size'       => $element['size'],
                                    'size_class' => $size_class
                                ];

                                $option_ids = [];

                                if(isset($element['options']) && !empty($element['options'])) {
                                    foreach ($element['options'] as $option) {
                                        $new_option             = Forms\Elements\Options::firstOrNew(['id' => $option['id']]);
                                        $new_option->element_id = $new_element->id;
                                        $new_option->save();

                                        /*
                                         * Setting translation
                                         */
                                        if(isset($option['labels']) && !empty($option['labels'])) {
                                            foreach($option['labels'] as $key => $translate){
                                                $new_option->setTranslation($key, ['label' => $translate]);
                                            }
                                        }

                                        $new_option->save();

                                        $option_ids[] = $new_option->id;
                                    }
                                }

                                Forms\Elements\Options::where('element_id', $new_element->id)->whereNotIn('id', $option_ids)->delete();

                            }
                        }

                        $new_section->elements()->sync($pivot_data);

                        $element_ids            = Forms\Sections\Elements::pluck('element_id');
                        $element_to_delete_ids  = Forms\Elements::whereNotIn('id', $element_ids)->pluck('id');

                        Forms\Elements::whereNotIn('id', $element_ids)->delete();
                        Forms\Elements\Options::whereIn('element_id', $element_to_delete_ids)->delete();

                    }
                }

                /*
                 * Delete sections and related items
                 */
                $section_to_delete_ids = Forms\Sections::where('form_id', $form->id)->whereNotIn('id', $section_ids)->pluck('id');

                Forms\Sections::where('form_id', $form->id)->whereNotIn('id', $section_ids)->delete();
                Forms\Sections\Elements::whereIn('section_id', $section_to_delete_ids)->delete();

                $element_ids            = Forms\Sections\Elements::pluck('element_id');
                $element_to_delete_ids  = Forms\Elements::whereNotIn('id', $element_ids)->pluck('id');

                Forms\Elements::whereNotIn('id', $element_ids)->delete();
                Forms\Elements\Options::whereIn('element_id', $element_to_delete_ids)->delete();

                DB::commit();

                /*
                 * Set OK status and return response
                 */

                Session::flash('message', 'Ett nytt formulär har lagts till');
                Session::flash('message-title', 'Success!');
                Session::flash('message-type', 'success');

                $var            = array();
                $var['status']  = 1;

                if(!isset($input['id']) || empty($input['id'])){
                    $var['redirect'] = route('rl_forms.admin.forms.index');
                } else {
                    $var['message']['title']    = 'Success!';
                    $var['message']['text']     = 'Formuläret har uppdaterats';
                }

                return response()->json($var);

            } catch (\Exception $e) {
                /*
                 * Somethin went wrong, do db rollback
                 */

                DB::rollback();

                throw $e;

                $var                        = array();
                $var['status']              = 0;
                $var['error']               = $e->getMessage();
                $var['line']                = $e->getLine();
                $var['message']['title']    = 'Error!';
                $var['message']['text']     = 'Unexpected error occurred';
                $var['input']               = $input;

                return response()->json($var);

            }
        }

    }


    public function drop()
    {
        $form_id = request()->get('id');

        DB::beginTransaction();

        try {

            Forms::where('id', $form_id)->delete();

            /*
             * Delete sections and related items
             */
            $section_to_delete_ids = Forms\Sections::where('form_id', $form_id)->pluck('id');

            Forms\Sections::where('form_id', $form_id)->delete();
            Forms\Sections\Elements::whereIn('section_id', $section_to_delete_ids)->delete();

            $element_ids            = Forms\Sections\Elements::pluck('element_id');
            $element_to_delete_ids  = Forms\Elements::whereNotIn('id', $element_ids)->pluck('id');

            Forms\Elements::whereIn('id', $element_to_delete_ids)->delete();
            Forms\Elements\Options::whereIn('element_id', $element_to_delete_ids)->delete();

            DB::commit();

            Session::flash('message', 'Formuläret har raderats');
            Session::flash('message-title', 'Success!');
            Session::flash('message-type', 'success');

            $var                = array();
            $var['status']      = 1;
            $var['redirect']    = route('rl_forms.admin.forms.index');

            return response()->json($var);

        } catch (\Exception $e) {

            DB::rollback();

            throw $e;

            $var = array();
            $var['status']              = 0;
            $var['error']               = $e->getMessage();
            $var['line']                = $e->getLine();
            $var['message']['title']    = 'Error!';
            $var['message']['text']     = 'Unexpected error occurred';
            $var['input']               = $input;

            return response()->json($var);

        }

    }

}