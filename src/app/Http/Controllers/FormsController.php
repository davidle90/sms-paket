<?php namespace Rocketlabs\Forms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Rocketlabs\Forms\App\Models\Forms;

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

        $form               = Forms::with('sections.elements.type')->find($id);
        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');
        $languages          = Languages::all()->keyBy('iso_name');

        return view('rl_forms::admin.pages.forms.edit', [
            'form'              => $form,
            'default_language'  => $default_language,
            'fallback_language' => $fallback_language,
            'languages'         => $languages
        ]);

        return view('', [

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

    public function store()
    {

        $input = [
            'id'        => request()->get('form_id', null),
            'labels'    => request()->get('labels', []),
            'slug'      => request()->get('slug'),
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
                 * Insert translatable values
                 */
                foreach($input['labels'] as $key => $translate){
                    $form->setTranslation($key, ['label' => $translate]);
                }

                $form->save();

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