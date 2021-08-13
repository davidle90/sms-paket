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

class SmsController extends Controller
{

	public function index(Request $request)
	{
        $sms                = $this->filter($request, false);
        $senders            = Senders::get();
        $smsables           = Smsables::get();

        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');

        $latest_refill      = Refills::orderBy('created_at', 'desc')->first();
        $used_sms_quantity  = Sms::where('sent_at', '>=', $latest_refill->created_at)->sum('quantity').'/'.($latest_refill->quantity + $latest_refill->remains);

        $now                = \Carbon\Carbon::now();
        $starts_at          = $now->copy()->startOfMonth();
        $ends_at            = $now->copy()->endOfMonth();

        $filter_array['timeline'] = [
            'Idag'              => [
                date('Y-m-d', $now->copy()->startOfDay()->timestamp),
                date('Y-m-d', $now->copy()->endOfDay()->timestamp),
            ],
            'Nuvarande vecka'   => [
                date('Y-m-d', $now->copy()->startOfWeek()->timestamp),
                date('Y-m-d', $now->copy()->endOfWeek()->timestamp),
            ],
            'Nuvarande månad'   => [
                date('Y-m-d', $now->copy()->startOfMonth()->timestamp),
                date('Y-m-d', $now->copy()->endOfMonth()->timestamp),
            ],
            'Nuvarande kvartal' => [
                date('Y-m-d', $now->copy()->startOfQuarter()->timestamp),
                date('Y-m-d', $now->copy()->endOfQuarter()->timestamp),
            ],
            'Nuvarande år'      => [
                date('Y-m-d', $now->copy()->startOfYear()->timestamp),
                date('Y-m-d', $now->copy()->endOfYear()->timestamp),
            ],
            'Sedan påfyllning'  => [
                date('Y-m-d', $latest_refill->created_at->copy()->timestamp),
                date('Y-m-d', $now->copy()->timestamp),
            ]
        ];

       return view('rl_sms::admin.pages.sms.index', [
           'sms'                => $sms,
           'filter_array'       => $filter_array,
           'starts_at'          => $starts_at,
           'ends_at'            => $ends_at,
           'used_sms_quantity'  => $used_sms_quantity,
           'latest_refill'      => $latest_refill,
           'senders'            => $senders,
           'smsables'           => $smsables,
           'default_language'   => $default_language,
           'fallback_language'  => $fallback_language
       ]);

	}

	public function view($id)
    {
        return 'view';
    }

    public function filter(Request $request, $ajax = true)
    {

        /**
         * Fill with filterconditions for users
         */

        $filter = [
            'query'                 => $request->get('query', $request->session()->get('sms_filter.query', null)),
            'order_by'              => $request->get('order_by', $request->session()->get('sms_filter.order_by', null)),
            'paginate'              => $request->get('results_per_page', $request->session()->get('sms_filter.paginate', null)),
            'direction'             => $request->get('direction', 'desc'),
            'daterange'             => $request->get('daterange', $request->session()->get('sms_filter.daterange', null))
        ];

        if($ajax == false){
            $filter['direction'] = $request->session()->get('sms_filter.direction', 'desc');
        }

        /*
         * Initialize the query for grabbing information
         */
        $smsQuery = Sms::query();
        //$smsQuery->with([]);

        /*
         * Filter by textsearch
         */
        if(isset($filter['query']) && !empty($filter['query'])){
            $smsQuery->where('sender_title', 'LIKE', '%'.$filter['query'].'%')
                ->orWhere('receiver_title', 'LIKE', '%'.$filter['query'].'%')
                ->orWhere('sender_phone', 'LIKE', '%'.$filter['query'].'%')
                ->orWhere('receiver_phone', 'LIKE', '%'.$filter['query'].'%');

            $request->session()->put('sms_filter.query', $filter['query']);

        }else{
            $request->session()->forget('sms_filter.query');
        }

        /*
         * Filter by daterange
         */
        if(isset($filter['daterange']) && !empty($filter['daterange'])){
            $date_array = explode(' - ', $filter['daterange']);

            $smsQuery->where('sent_at', '>=', $date_array[0].' 00:00:01');
            $smsQuery->where('sent_at', '<=', $date_array[1].' 23:59:59');

            $request->session()->put('sms_filter.daterange', $filter['daterange']);

        }else{
            $request->session()->forget('sms_filter.daterange');
        }

        if(isset($filter['direction']) && !empty($filter['direction'])){
            $direction = $filter['direction'];
            $request->session()->put('sms_filter.direction', $filter['direction']);
        } else{
            $direction = 'asc';
            $request->session()->put('sms_filter.direction', $direction);
        }
        
        /*
         * Filter by variable order_by and direction (desc -> default, asc if checkbox is marked
         */
        if(isset($filter['order_by']) && !empty($filter['order_by'])){
            $smsQuery->orderBy($filter['order_by'], $direction);
            
            $request->session()->put('sms_filter.order_by', $filter['order_by']);
        }else{
            if($direction == 'asc'){
                $direction = 'desc';
            }
            $smsQuery->orderBy('created_at', $direction);
        }

        if(isset($filter['paginate']) && !empty($filter['paginate'])){
            $request->session()->put('sms_filter.paginate', $filter['paginate']);
            $paginate = $filter['paginate'];
        }else{
            $paginate = 50;
        }

        $sms = $smsQuery->paginate($paginate);
        $sms->withPath(route('rl_sms.admin.sms.index'));

        /*
         * Returns the result as a JSON so the site can read the information
         */
        if($request->wantsJson()){

            $response['view'] = view('rl_sms::admin.pages.sms.includes.table', [
                'sms' => $sms
            ])->render();

            return response()->json($response);
        }else{
            return $sms;
        }
    }

    public function clear_filter()
    {
        session()->forget('sms_filter');

        $response = [
            'status' => 1
        ];

        return response()->json($response);
    }

    public function chart(Request $request)
    {
        $daterange  = $request->get('daterange', '1900-01-01 - 2100-01-01');
        $date_array = explode(' - ', $daterange);

        $sms = Sms::orderBy('sent_at', 'asc')
            ->where('sent_at', '>=', $date_array[0].' 00:00:01')
            ->where('sent_at', '<=', $date_array[1].' 23:59:59')
            ->get();

        $data        = [
            'labels'    => [],
            'datasets'  => [
                [
                    'label'             => 'Antal utskick',
                    'data'              => [],
                    'backgroundColor'   => '#3292e0',
                    'borderColor'       => '#3292e0',
                    'borderWidth'       => 1
                ],
                [
                    'label'             => 'Använda SMS',
                    'data'              => [],
                    'backgroundColor'   => '#c1def5',
                    'borderColor'       => '#c1def5',
                    'borderWidth'       => 1
                ]
            ]
        ];

        $sms_per_day = [];

        if($date_array[0] !== $date_array[1]) {
            /**  Getting sent SMS per day **/
            foreach ($sms as $item){
                $date = $item->sent_at->copy()->toDateString();

                if(isset($sms_per_day[$date])) {
                    $sms_per_day[$date]['net']      += 1;
                    $sms_per_day[$date]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_day[$date]['net']      = 1;
                    $sms_per_day[$date]['gross']    = (1 * $item->quantity);
                }
            }

            foreach ($sms_per_day as $date => $item){
                $data['labels'][]               = $date;
                $data['datasets'][0]['data'][]  = $item['net'];
                $data['datasets'][1]['data'][]  = $item['gross'];
            }
        } else {

            /**  Getting sent SMS per Hour **/
            foreach ($sms as $item){
                $hour = $item->sent_at->copy()->hour;

                if(isset($sms_per_hour[$hour])) {
                    $sms_per_hour[$hour]['net']      += 1;
                    $sms_per_hour[$hour]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_hour[$hour]['net']      = 1;
                    $sms_per_hour[$hour]['gross']    = (1 * $item->quantity);
                }
            }

            for($i = 0; $i < 24; $i++){
                if(!isset($sms_per_hour[$i]) || empty($sms_per_hour[$i])) {
                    $sms_per_hour[$i]['net']    = 0;
                    $sms_per_hour[$i]['gross']  = 0;
                }

                $data['labels'][]               = ($i < 10) ? '0'.$i.':00' : $i.':00';
                $data['datasets'][0]['data'][]  = $sms_per_hour[$i]['net'];
                $data['datasets'][1]['data'][]  = $sms_per_hour[$i]['gross'];
            }
        }

        return response()->json($data);
    }

    public function send(Request $request)
    {

        $input = [
            'sender_id' => $request->get('sender_id', null),
            'message'   => $request->get('message', null),
            'receivers' => $request->get('receivers', null),
        ];

        $validator = Validator::make($input, [
            'sender_id' => 'required',
            'message'   => 'required',
            'receivers' => 'required',
        ]);

        if($validator->fails()){

            $var = array();
            $var['status']  = 0;
            $var['errors']  = $validator->errors()->toArray();
            $var['message']['title']    = 'Form validation error!';
            $var['message']['text']     = 'Some form value is missing or not properly filled out, please check your input and try again';

            return response()->json($var);

        } else {

            pre($input);

            //DB::beginTransaction();

            try {

                //DB::commit();

                Session::flash('message', 'Meddelande skickat!');
                Session::flash('message-title', 'Lyckad åtgärd!');
                Session::flash('message-type', 'success');

                $response = [
                    'status'  => 1,
                    'message' => [
                        'title' => 'Lyckad åtgärd!',
                        'text'  => 'Meddelande skickat!'
                    ],
                    'redirect' => route('rl_sms.admin.sms.index')
                ];

                return response()->json($response);

            } catch (\Exception $e) {

                //DB::rollback();

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