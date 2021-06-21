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

        return view('rl_forms::admin.pages.forms.edit', [
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language,
            'languages'         => $languages
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

        $form               = Forms::find($id);
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
        $default_language   = Config::get('app.locale');
        $table              = \rl_tables::tables_model()::where('id', $table_id)->with('data')->first();

        return view('rl_forms::admin.pages.forms.templates.card', [
            'section_index'             => $section_index,
            'element_index'             => $element_index,
            'element_label'             => $label,
            'element_description'       => $description,
            'element_required_text'     => $required_text,
            'element_required'          => $required,
            'type_id'                   => $type_id,
            'default_language'          => $default_language,
            'table'                     => $table,
            'options'                   => $options
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
        pre(request()->all());
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

                if(isset($input['sections']) && !empty($input['sections'])) {
                    $section_ids = [];

                    foreach($input['sections'] as $section) {
                        $new_section                = Forms\Sections::firstOrNew(['id' => $section['id']]);
                        $new_section->form_id       = $form->id;
                        $new_section->sort_order    = $section['sort_order'];
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

                        if(isset($section['elements']) && !empty($section['elements'])) {
                            $pivot_data = [];

                            foreach($section['elements'] as $element) {
                                $new_element            = Forms\Elements::firstOrNew(['id' => $element['id']]);
                                $new_element->slug      = $element['slug'];
                                $new_element->type_id   = $element['type_id'];
                                $new_element->validator = $element['validator'];
                                $new_element->table_id  = $element['table_id'];
                                $new_element->save();

                                /*
                                 * Setting translations
                                 */
                                foreach($section['labels'] as $key => $translate){
                                    $new_element->setTranslation($key, ['label' => $translate]);
                                }

                                foreach($section['descriptions'] as $key => $translate){
                                    $new_element->setTranslation($key, ['description' => $translate]);
                                }

                                foreach($section['required'] as $key => $translate){
                                    $new_element->setTranslation($key, ['required' => $translate]);
                                }

                                $new_element->save();

                                $size_class = '';

                                if(isset($element['size']) && !empty($element['size'])) {
                                    $size_class += (isset($element['size']['xs'])) ? 'col-'.$element['size']['xs'].' ' : 'col-12 ';

                                    foreach($element['size'] as $key => $value) {
                                        if($key === 'xs') continue;

                                        if(isset($value)) {
                                            $size_class += 'col-'.$key.'-'.$value.' ';
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
                                        foreach($option['labels'] as $key => $translate){
                                            $new_element->setTranslation($key, ['label' => $translate]);
                                        }

                                        $new_option->save();

                                        $option_ids[] = $new_option->id;
                                    }
                                }

                            }

                            $new_section->elements()->sync($pivot_data);
                        }
                    }
                }

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


    public function drop(Request $request)
    {

        return response()->json($response);

    }

}