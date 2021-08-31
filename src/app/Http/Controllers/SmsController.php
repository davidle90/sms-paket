<?php namespace Rocketlabs\Sms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;

use rl_sms;
use DB;

use Rocketlabs\Languages\App\Models\Languages;
use Rocketlabs\Notifications\App\Facades\Notifications;
use Rocketlabs\Sms\App\Models\NexmoReceipts;
use Rocketlabs\Sms\App\Models\Receivers;
use Rocketlabs\Sms\App\Models\Refills;
use Rocketlabs\Sms\App\Models\Senders;
use Rocketlabs\Sms\App\Models\Sms;
use Rocketlabs\Sms\App\Models\Smsables;
use Validator;
use Carbon\Carbon;
use Rocketlabs\Notifications\App\Notifications\Notifier;
use Notification;

class SmsController extends Controller
{

	public function index(Request $request)
	{
        $sms                = $this->filter($request, false);
        $senders            = Senders::get();
        $smsables           = Smsables::get();

        $default_language   = Config::get('app.locale');
        $fallback_language  = Config::get('app.fallback_locale');
        $sms_price          = Config::get('rl_sms.price');
        $refill_threshold   = Config::get('rl_sms.refill.threshold');
        $refill_amount      = Config::get('rl_sms.refill.amount');

        $latest_refill      = Refills::orderBy('created_at', 'desc')->first();
        $refill_ids         = Refills::pluck('id')->toArray();

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
            'Sedan påfyllning'  => (!empty($latest_refill)) ? [
                date('Y-m-d', $latest_refill->created_at->copy()->timestamp),
                date('Y-m-d', $now->copy()->timestamp),
            ] : [
                    date('Y-m-d', $now->copy()->startOfYear()->timestamp),
                    date('Y-m-d', $now->copy()->timestamp)
                ],
        ];

        /*
         * Mark notifications as read
         */
        auth()->user()
            ->unreadNotifications
            ->whereIn('type', ['sms_refilled'])
            ->whereIn('type_id', $refill_ids)
            ->markAsRead();

       return view('rl_sms::admin.pages.sms.index', [
           'sms'                => $sms,
           'filter_array'       => $filter_array,
           'starts_at'          => $starts_at,
           'ends_at'            => $ends_at,
           'latest_refill'      => $latest_refill,
           'senders'            => $senders,
           'smsables'           => $smsables,
           'default_language'   => $default_language,
           'fallback_language'  => $fallback_language,
           'sms_price'          => $sms_price,
           'refill_threshold'   => $refill_threshold,
           'refill_amount'      => $refill_amount
       ]);

	}

	public function view($id)
    {
        $sms = Sms::with('nexmo.receipt', 'message')->find($id);

        $mcc_mnc_list = json_decode(file_get_contents(base_path().'/vendor/rocketlabs/sms/src/resources/assets/vendor/mcc-mnc-list/mcc-mnc-list.json'), true);
        $mcc_mnc_list = collect($mcc_mnc_list)->map(function($item){
           $item['mccmnc'] = $item['mcc'].$item['mnc'];
           return $item;
        })->keyBy('mccmnc');

        return view('rl_sms::admin.pages.sms.view', [
            'sms'           => $sms,
            'mcc_mnc_list'  => $mcc_mnc_list,
        ]);
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

        $smsQuery->with(['nexmo.receipt']);

        /*
         * Filter by textsearch
         */
        if(isset($filter['query']) && !empty($filter['query'])){
            $smsQuery->where('sender_title', 'LIKE', '%'.$filter['query'].'%')
                ->orWhere('receiver_title', 'LIKE', '%'.$filter['query'].'%')
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
        $start_time = strtotime($date_array[0]);
        $end_time   = strtotime($date_array[1]);
        $days       = ($end_time - $start_time) / (60 * 60 * 24);

        $sms = Sms::orderBy('sent_at', 'asc')
            ->where('sent_at', '>=', $date_array[0].' 00:00:01')
            ->where('sent_at', '<=', $date_array[1].' 23:59:59')
            ->get();

        $receipts = NexmoReceipts::orderBy('message_timestamp', 'asc')
            ->where('message_timestamp', '>=', $date_array[0].' 00:00:01')
            ->where('message_timestamp', '<=', $date_array[1].' 23:59:59')
            ->where('status', 'failed')
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
                ],
                [
                    'label'             => 'Misslyckade SMS',
                    'data'              => [],
                    'backgroundColor'   => '#f5c1c4',
                    'borderColor'       => '#f5c1c4',
                    'borderWidth'       => 1
                ],
            ]
        ];

        if($date_array[0] !== $date_array[1]) {

            $refills        = Refills::orderBy('created_at', 'desc')->get();
            $refill_dates   = [];
            $highest_value  = 0;
            $sms_per_day    = [];
            $failed_per_day = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->format('Y-m-d');
            }

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

                if($sms_per_day[$date]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_day[$date]['gross'];
                }
            }

            /** Getting failed SMS per day **/
            foreach ($receipts as $receipt){
                $date = Carbon::parse($receipt->message_timestamp)->copy()->toDateString();

                if(isset($failed_per_day[$date])) {
                    $failed_per_day[$date]  += 1;
                } else {
                    $failed_per_day[$date]  = 1;
                }

                if($failed_per_day[$date] >= $highest_value) {
                    $highest_value = $failed_per_day[$date];
                }
            }

            $data['datasets'][3] = [
                'label'             => 'Påfyllningar',
                'data'              => [],
                'backgroundColor'   => 'red',
                'borderColor'       => 'red',
                'barThickness'      => 1,
            ];

            for($i = 0; $i <= $days; $i++){
                $date               = Carbon::parse($date_array[0])->copy()->addDay($i)->format('Y-m-d');
                $data['labels'][]   = $date;

                if(isset($sms_per_day[$date]) && !empty($sms_per_day[$date])) {
                    $data['datasets'][0]['data'][]  = $sms_per_day[$date]['net'];
                    $data['datasets'][1]['data'][]  = $sms_per_day[$date]['gross'];
                } else {
                    $data['datasets'][0]['data'][] = 0;
                    $data['datasets'][1]['data'][] = 0;
                }

                if(isset($failed_per_day[$date]) && !empty($failed_per_day[$date])) {
                    $data['datasets'][2]['data'][] = $failed_per_day[$date];
                } else {
                    $data['datasets'][2]['data'][] = 0;
                }

                if(in_array($date, $refill_dates)) {
                    $data['datasets'][3]['data'][] = $highest_value;
                } else {
                    $data['datasets'][3]['data'][] = 0;
                }
            }

        } else {

            $sms_per_hour    = [];
            $failed_per_hour = [];

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

            /**  Getting failed SMS per Hour **/
            foreach ($receipts as $receipt){
                $hour = Carbon::parse($receipt->message_timestamp)->copy()->hour;

                if(isset($failed_per_hour[$hour])) {
                    $failed_per_hour[$hour] += 1;
                } else {
                    $failed_per_hour[$hour] = 1;
                }
            }

            for($i = 0; $i < 24; $i++){
                if(!isset($sms_per_hour[$i]) || empty($sms_per_hour[$i])) {
                    $sms_per_hour[$i]['net']    = 0;
                    $sms_per_hour[$i]['gross']  = 0;
                }

                if(!isset($failed_per_hour[$i]) || empty($failed_per_hour[$i])) {
                    $failed_per_hour[$i] = 0;
                }

                $data['labels'][]               = ($i < 10) ? '0'.$i.':00' : $i.':00';
                $data['datasets'][0]['data'][]  = $sms_per_hour[$i]['net'];
                $data['datasets'][1]['data'][]  = $sms_per_hour[$i]['gross'];
                $data['datasets'][2]['data'][]  = $failed_per_hour[$i];
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

            try {

                rl_sms::send($input['sender_id'], $input['receivers'], $input['message']);

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

    public function webhook_receipts(Request $request)
    {

        // Initiate nexmo logger
        $logger = with(new Logger('nexmo'))->pushHandler(
            new StreamHandler(storage_path('logs/nexmo.log'), \Monolog\Logger::DEBUG)
        );

        //$receipt = \Vonage\SMS\Webhook\Factory::createFromRequest($request_nexmo);
        //$logger->info('receipt', ['receipt' => $receipt]);
        $is_valid = true;
        //$is_valid = $this->validate_jwt($request, $logger);

        DB::beginTransaction();

        if($is_valid) {
            try {

                $input = [
                    'message_id'        => $request->get('messageId', null),
                    'message_timestamp' => $request->get('message-timestamp', null),
                    'msisdn'            => $request->get('msisdn', null),
                    'scts'              => $request->get('scts', null),
                    'price'             => $request->get('price', null),
                    'network'           => $request->get('network-code', null),
                    'status'            => $request->get('status', null),
                    'error_code'        => $request->get('err-code', null)
                ];

                $new_receipt = NexmoReceipts::firstOrNew(['message_id' => $input['message_id']]);
                $new_receipt->message_timestamp = $input['message_timestamp'];
                $new_receipt->msisdn            = $input['msisdn'];
                $new_receipt->scts              = $input['scts'];
                $new_receipt->price             = $input['price'];
                $new_receipt->network           = $input['network'];
                $new_receipt->status            = $input['status'];
                $new_receipt->error_code        = $input['error_code'];
                $new_receipt->save();

                DB::commit();

                $logger->info('Webhook received.', $input);

                return response()->json(['message' => 'Receipt successfully received'], 200);

            } catch (\Exception $e) {

                DB::rollBack();

                $logger->error('Database transaction failed.',  ['error_message' => $e->getMessage()]);

                throw $e;

            }
        }

    }

    public function validate_jwt($request, $logger)
    {

        try {

            $headers = getallheaders();
            $authHeader = $headers['Authorization'];
            $token      = substr($authHeader, 7);
            $secret     = config('nexmo.signature_secret');

            $key            = \Lcobucci\JWT\Signer\Key\InMemory::plainText($secret);
            $configuration  = \Lcobucci\JWT\Configuration::forSymmetricSigner(
                new \Lcobucci\JWT\Signer\Hmac\Sha256(),
                $key
            );

            $logger->info('header', ['authHeader' => $authHeader, 'token' => $token]);

            $token = $configuration->parser()->parse($token);

            $configuration->validator()->validate(
                $token,
                new \Lcobucci\JWT\Validation\Constraint\SignedWith($configuration->signer(), $configuration->signingKey())
            );

            return true;

        } catch (\Exception $e) {

            $logger->error('Verification of webhook failed.',  ['error_message' => $e->getMessage()]);

            throw $e;

        }
    }

}