<?php namespace Rocketlabs\Sms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

use DB;
use Rocketlabs\Languages\App\Models\Languages;
use Rocketlabs\Sms\App\Models\Receivers;
use Rocketlabs\Sms\App\Models\Refills;
use Rocketlabs\Sms\App\Models\Senders;
use Rocketlabs\Sms\App\Models\Sms;
use Rocketlabs\Sms\App\Models\Smsables;
use Validator;
use Carbon\Carbon;

class SendersController extends Controller
{

	public function index(Request $request)
	{
	    $senders = Senders::get();

        return view('rl_sms::admin.pages.senders.index', [
            'senders' => $senders
        ]);
	}

	public function edit($id)
    {
        $sender = Senders::find($id);

        return view('rl_sms::admin.pages.senders.edit', [
            'sender' => $sender
        ]);
    }

    public function create()
    {
        return view('rl_sms::admin.pages.senders.edit');
    }

    public function store(Request $request)
    {
        $input = [
            'id'                => $request->get('id', null),
            'name'              => $request->get('name', null),
            'sms_label'         => $request->get('sms_label', null),
            'slug'              => $request->get('slug', null),
            'description'       => $request->get('description', null)
        ];

        $validator = Validator::make($input, [
            'name'              => 'required',
            'sms_label'         => 'required',
            'slug'              => 'required',
        ]);

        if($validator->fails()){

            $var = array();
            $var['status']  = 0;
            $var['errors']  = $validator->errors()->toArray();
            $var['message']['title']    = 'Form validation error!';
            $var['message']['text']     = 'Some form value is missing or not properly filled out, please check your input and try again';

            return response()->json($var);

        } else {

            DB::beginTransaction();

            try {

                $new_sender = Senders::firstOrNew(['id' => $input['id']]);
                $new_sender->name           = $input['name'];
                $new_sender->sms_label      = $input['sms_label'];
                $new_sender->slug           = $input['slug'];
                $new_sender->description    = $input['description'];
                $new_sender->save();

                DB::commit();

                Session::flash('message', 'Avsändaren har blivit sparad!');
                Session::flash('message-title', 'Lyckad åtgärd!');
                Session::flash('message-type', 'success');

                $response = [
                    'status'  => 1,
                    'message' => [
                        'title' => 'Lyckad åtgärd!',
                        'text'  => 'Avsändaren har blivit sparad!'
                    ],
                    'redirect' => route('rl_sms.admin.senders.index')
                ];

                return response()->json($response);

            } catch (\Exception $e) {

                DB::rollback();

                //throw $e;

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

    public function drop(Request $request)
    {
        $input = [
            'id' => $request->get('id', null),
        ];

        $validator = Validator::make($input, [
            'id' => 'required',
        ]);

        if($validator->fails()){

            $var = array();
            $var['status']  = 0;
            $var['errors']  = $validator->errors()->toArray();
            $var['message']['title']    = 'Form validation error!';
            $var['message']['text']     = 'Some form value is missing or not properly filled out, please check your input and try again';

            return response()->json($var);

        } else {

            DB::beginTransaction();

            try {

                $sender = Senders::find($input['id']);
                $sender->delete();

                DB::commit();

                Session::flash('message', 'Avsändaren har blivit borttagen!');
                Session::flash('message-title', 'Lyckad åtgärd!');
                Session::flash('message-type', 'success');

                $response = [
                    'status'  => 1,
                    'message' => [
                        'title' => 'Lyckad åtgärd!',
                        'text'  => 'Avsändaren har blivit borttagen!'
                    ],
                    'redirect' => route('rl_sms.admin.senders.index')
                ];

                return response()->json($response);

            } catch (\Exception $e) {

                DB::rollback();

                //throw $e;

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

}