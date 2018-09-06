<?php namespace Rocketlabs\Forms\App\Http\Controllers;

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use Rocketlabs\Forms\App\Models\Forms\Lists\Elements as ListElements;

use Rocketlabs\Forms\App\Models\Forms;
use Rocketlabs\Forms\App\Models\Forms\Elements as FormsElements;
use Rocketlabs\Forms\App\Models\Forms\Elements\Options as FormsElementsOptions;

use Rocketlabs\Forms\App\Models\Forms\Response as FormsResponse;
use Rocketlabs\Forms\App\Models\Forms\Sections as FormsSections;
use Rocketlabs\Forms\App\Models\Forms\Response\Data as FormsResponseData;
use Rocketlabs\Forms\App\Models\Forms\Response\Data\Options as FormsResponseDataOptions;

use DB;
use Validator;

class FormsController extends Controller
{

	public function index()
	{
        $forms = Forms::orderBy('label', 'asc')->get();

		return view('rl_forms::admin.pages.forms.index', [
            'forms' => $forms
        ]);
	}

	public function create()
    {

        $listElements = ListElements::enabled()
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        return view('rl_forms::admin.pages.forms.edit', [
            'listElements'	=> $listElements
        ]);

    }

	public function edit($id)
    {

        $listElements = ListElements::enabled()
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();


        $form = Forms::withCount('elements', 'sections')->with([
            'sections' => function($query){
                $query->orderBy('sort_order', 'asc');
            },
            'sections.elements' => function($query){
                $query->orderBy('sort_order', 'asc');
            },
            'sections.elements.template',
            'sections.elements.options' => function($query){
                $query->other(0)->orderBy('sort_order', 'asc');
            }
        ])->find($id);


        return view('rl_forms::admin.pages.forms.edit', [
            'listElements'	=> $listElements,
            'form' => $form
        ]);
    }

	public function view($id)
    {

    }

    public function store(Request $request)
    {

        $input = [
            'form_id' => $request->get('form_id'),
            'label'     => $request->get('label'),
            'section' => $request->get('section', [])
        ];


        try {

            $form = Forms::findOrNew($input['form_id']);
            $form->label = $input['label'];
            $form->save();

            /*
            * Save sections
            */
            $sectionsIds = [];
            $elementsIds = [];
            $notifyNewElements = [];


            foreach($input['section'] as $skey => $section){
                $newSection = FormsSections::findOrNew($section['id']);
                $newSection->form_id = $form->id;
                $newSection->label = '';
                $newSection->description = '';
                $newSection->sort_order = $section['sort_order'];
                $newSection->save();
                $sectionsIds[] = $newSection->id;

                /*
                 * Save elements
                 */
                if(isset($section['element']) && !empty($section['element'])){

                    foreach($section['element'] as $key => $element){

                        $newElement = FormsElements::findOrNew($element['id']);
                        $newElement->form_id = $form->id;
                        $newElement->section_id = $newSection->id;
                        $newElement->list_element_id = $element['list_element_id'];
                        $newElement->label = $element['label'];
                        $newElement->help_text = $element['help_text'];
                        $newElement->required_text = '';
                        $newElement->attr_required = isset($element['required']) ? 0 : 1;
                        $newElement->attr_disabled = 0;
                        $newElement->attr_readonly = 0;
                        $newElement->attr_novalidate = 0;
                        $newElement->attr_autocomplete = 0;
                        $newElement->attr_multiple = isset($element['multiple']) ? 1 : 0;
                        $newElement->hidden = 0;
                        $newElement->other = isset($element['other']) ? 1 : 0;;
                        $newElement->default_value = '';
                        $newElement->sort_order = isset($element['sort_order']) ? $element['sort_order'] : 0;

                        $notifyE = false;
                        if(!$newElement->exists){
                            $notifyE = true;
                        }

                        $newElement->save();

                        if($notifyE){
                            $notifyNewElements[$key]['id'] = $newElement->id;
                        }

                        $elementsIds[] = $newElement->id;

                        // If options
                        $optionIds = [];
                        if(isset($element['options']) && !empty($element['options'])){
                            foreach($element['options'] as $key2 => $option){
                                $newOption = FormsElementsOptions::findOrNew($option['id']);
                                $newOption->element_id = $newElement->id;
                                $newOption->label = $option['label'];
                                $newOption->other = $option['other'];
                                $newOption->sort_order = $option['sort_order'];

                                $notifyO = false;
                                if(!$newOption->exists){
                                    $notifyO = true;
                                }

                                $newOption->save();

                                if($notifyO){
                                    $notifyNewElements[$key]['options'][$key2] = $newElement->id;
                                }

                                $optionIds[] = $newOption->id;
                            }
                        }

                        FormsElementsOptions::where('element_id',$newElement->id)->whereNotIn('id',$optionIds)->delete();
                    }
                }

                FormsElements::where('form_id', $form->id)->whereNotIn('id',$elementsIds)->delete();

            }
            FormsSections::where('form_id', $form->id)->whereNotIn('id',$sectionsIds)->delete();

            DB::commit();

            $response = [
                'status' => 1,
                'message' => [
                    'title' => 'Success!',
                    'text' => 'Survey have been successfully saved'
                ],
                'input' => $input,
                'newElementsNotify' => $notifyNewElements
            ];

            if(!isset($input['form_id']) || empty($input['form_id'])){
                $response['redirect'] = route('rl_forms.admin.forms.edit', ['id' => $form->id]);
            }

        } catch(\Exception $e){

            $response = [
                'status' => 0,
                'message' => [
                    'title' => 'Error!',
                    'text' => 'An unexpected error has occurred!'
                ],
                'input' => $input,
                'debug' => $e->getMessage().' :: '.$e->getLine()
            ];

        }

        return response()->json($response);

    }


    public function destroy(Request $request)
    {

        $input = [
            'form_id'	=> $request->get('form_id'),
        ];

        $validator = Validator::make($input,
            [
                'form_id'   => 'required|integer',
            ]
        );

        if($validator->fails()){

            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray(),
                'message' => [
                    'title' => 'Form validation error!',
                    'text' => 'Some form value is missing or not properly filled out, please check your input and try again.'
                ]
            ];

            return response()->json($response);

        }

        /*
         * Begin database transaction and start inserting into database
         */

        DB::beginTransaction();

        try {

            /*
             * Delete all event related stuffs
             */
            Forms::where('id', $input['form_id'])->delete();
            FormsSections::where('form_id', $input['form_id'])->delete();
            FormsElements::where('form_id', $input['form_id'])->delete();

            /*
             * No errors, commit database changes
             */

            DB::commit();

            /*
             * Set OK status and return response
             */
            $response = [
                'status' => 1,
                'message' => [
                    'title' => 'Success!',
                    'text' => 'The form have been deleted'
                ],
                'redirect' => route('rl_forms.admin.forms.index')
            ];



        } catch(\Exception $e) {

            /*
             * Somethin went wrong, do db rollback
             */

            DB::rollback();

            $response = [
                'status' => 0,
                'errors' => $validator->errors()->toArray(),
                'message' => [
                    'title' => 'Error!',
                    'text' => 'Unexpected error occurred.'
                ]
            ];


        }

        return response()->json($response);

    }

    public function template($template = null, Request $request)
    {
        if(!is_null($template)){

            return view('rl_forms::admin.pages.forms.templates.'.$template,[
                'i' => $request->get('count',0),
                'y' => $request->get('sections',0)
            ]);
        }
        return '';

    }


}