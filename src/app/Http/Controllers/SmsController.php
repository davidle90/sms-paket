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
        $sms                    = $this->filter($request, false);
        $senders                = Senders::get();
        $smsables               = Smsables::get();

        $default_language       = Config::get('app.locale');
        $fallback_language      = Config::get('app.fallback_locale');
        $sms_unit_price         = Config::get('rl_sms.price');
        $sms_unit_price_last    = $sms_unit_price;
        $refill_threshold       = Config::get('rl_sms.refill.threshold');
        $refill_amount          = Config::get('rl_sms.refill.amount');

        $last_refill            = rl_sms::get_last_refill();
        $refill_ids             = Refills::pluck('id')->toArray();

        $remaining_sms_pot      = 0;

        if(!empty($last_refill)){

            // Set sms unit price to last refill
            if(!is_null($last_refill->sms_unit_price)){
                $sms_unit_price_last = $last_refill->sms_unit_price;
            }

            $sent_sms_since_last_refill = Sms::where('sent_at', '>=', $last_refill->created_at)->sum('quantity');
            //$sent_sms_cost = $sent_sms_since_last_refill / $sms_unit_price_last;

            $remaining_sms_pot  = (!is_null($last_refill->total)) ? $last_refill->total : ($last_refill->quantity + $last_refill->remains);
            $remaining_sms_pot  = floor($remaining_sms_pot - $sent_sms_since_last_refill);
        }

        $now                = \Carbon\Carbon::now();
        $starts_at          = $now->copy()->startOfMonth()->format('Y-m-d');
        $ends_at            = $now->copy()->endOfMonth()->format('Y-m-d');

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
            'Sedan påfyllning'  => (!empty($last_refill)) ? [
                date('Y-m-d H:i:s', $last_refill->created_at->copy()->timestamp),
                date('Y-m-d H:i:s', $now->copy()->timestamp),
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
           'latest_refill'      => $last_refill,
           'senders'            => $senders,
           'smsables'           => $smsables,
           'default_language'   => $default_language,
           'fallback_language'  => $fallback_language,
           'sms_price'          => $sms_unit_price,
           'sms_price_last'     => $sms_unit_price_last,
           'refill_threshold'   => $refill_threshold,
           'refill_amount'      => $refill_amount,
           'remaining_sms_pot'  => $remaining_sms_pot
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
            'daterange'             => $request->get('daterange', $request->session()->get('sms_filter.daterange', null)),
            'daterange_full'        => $request->get('daterange_full', $request->session()->get('sms_filter.daterange_full', null))
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
        if(isset($filter['daterange_full']) && !empty($filter['daterange_full'])){
            $date_array = explode(' - ', $filter['daterange_full']);

            $smsQuery->where('sent_at', '>=', $date_array[0]);
            $smsQuery->where('sent_at', '<=', $date_array[1]);

            $request->session()->put('sms_filter.daterange', $filter['daterange']);
            $request->session()->put('sms_filter.daterange_full', $filter['daterange_full']);

        } elseif(isset($filter['daterange']) && !empty($filter['daterange'])){
            $date_array = explode(' - ', $filter['daterange']);

            $smsQuery->where('sent_at', '>=', $date_array[0]);
            $smsQuery->where('sent_at', '<=', $date_array[1]);

            $request->session()->put('sms_filter.daterange', $filter['daterange']);
            $request->session()->forget('sms_filter.daterange_full');

        } else{
            $request->session()->forget('sms_filter.daterange');
            $request->session()->forget('sms_filter.daterange_full');
        }

        //pre($date_array);

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
        $daterange  = !empty($request->get('daterange_full')) ? $request->get('daterange_full') : $request->get('daterange', '2000-01-01 - 2100-01-01');
        $date_array = explode(' - ', $daterange);
        $days       = Carbon::parse($date_array[0])->diffInDays(Carbon::parse($date_array[1]));
        $months     = Carbon::parse($date_array[0])->diffInMonths(Carbon::parse($date_array[1]));
        $quarters   = Carbon::parse($date_array[0])->diffInQuarters(Carbon::parse($date_array[1]));
        $years      = Carbon::parse($date_array[0])->diffInYears(Carbon::parse($date_array[1]));

        $sms = Sms::orderBy('sent_at', 'asc')
            ->where('sent_at', '>=', $date_array[0])
            ->where('sent_at', '<=', $date_array[1])
            ->get();

        $receipts = NexmoReceipts::orderBy('message_timestamp', 'asc')
            ->where('message_timestamp', '>=', $date_array[0])
            ->where('message_timestamp', '<=', $date_array[1])
            ->where('status', 'failed')
            ->has('response.sms')
            ->get();

        $refills = Refills::orderBy('created_at', 'desc')
            ->where('created_at', '>=', $date_array[0])
            ->where('created_at', '<=', $date_array[1])
            ->get();

        $refill_dates   = [];
        $highest_value  = 0;

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
                    'backgroundColor'   => '#fabb3d',
                    'borderColor'       => '#fabb3d',
                    'borderWidth'       => 1
                ],
                [
                    'label'             => 'Misslyckade SMS',
                    'data'              => [],
                    'backgroundColor'   => '#ff5454',
                    'borderColor'       => '#ff5454',
                    'borderWidth'       => 1
                ],
                [
                    'label'             => 'Påfyllningar',
                    'data'              => [],
                    'backgroundColor'   => '#5cb45b',
                    'borderColor'       => '#5cb45b',
                    'barThickness'      => 3
                ]
            ]
        ];

        if($days == 0) {

            /*
            * Singe day, 24h formatting
            */
            $sms_per_hour    = [];
            $failed_per_hour = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->copy()->hour;
            }

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

                if($sms_per_hour[$hour]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_hour[$hour]['gross'];
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

                if($failed_per_hour[$hour] >= $highest_value) {
                    $highest_value = $failed_per_hour[$hour];
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

                if(in_array($i, $refill_dates)) {
                    $data['datasets'][3]['data'][] = $highest_value;
                } else {
                    $data['datasets'][3]['data'][] = 0;
                }

                $data['labels'][]               = ($i < 10) ? '0'.$i.':00' : $i.':00';
                $data['datasets'][0]['data'][]  = $sms_per_hour[$i]['net'];
                $data['datasets'][1]['data'][]  = $sms_per_hour[$i]['gross'];
                $data['datasets'][2]['data'][]  = $failed_per_hour[$i];
            }

        } elseif($days <= 31) {

            /*
             * Month, day formatting
             */
            $sms_per_day    = [];
            $failed_per_day = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->copy()->format('Y-m-d');
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

        } elseif($days <= 182) {

            /*
             * Quarter, week formatting, everything under 6 months
             */
            $sms_per_week    = [];
            $failed_per_week = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->copy()->startOfWeek()->format('Y-m-d');
            }

            /**  Getting sent SMS per week **/
            foreach ($sms as $item){
                $date = $item->sent_at->copy()->startOfWeek()->toDateString();

                if(isset($sms_per_week[$date])) {
                    $sms_per_week[$date]['net']      += 1;
                    $sms_per_week[$date]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_week[$date]['net']      = 1;
                    $sms_per_week[$date]['gross']    = (1 * $item->quantity);
                }

                if($sms_per_week[$date]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_week[$date]['gross'];
                }
            }

            /** Getting failed SMS per week **/
            foreach ($receipts as $receipt){
                $date = Carbon::parse($receipt->message_timestamp)->copy()->startOfWeek()->toDateString();

                if(isset($failed_per_week[$date])) {
                    $failed_per_week[$date]  += 1;
                } else {
                    $failed_per_week[$date]  = 1;
                }

                if($failed_per_week[$date] >= $highest_value) {
                    $highest_value = $failed_per_week[$date];
                }
            }

            for($i = 0; $i <= $days ; $i += 7){
                $date               = Carbon::parse($date_array[0])->copy()->addDay($i)->startOfWeek()->format('Y-m-d');
                $data['labels'][]   = $date;

                if(isset($sms_per_week[$date]) && !empty($sms_per_week[$date])) {
                    $data['datasets'][0]['data'][]  = $sms_per_week[$date]['net'];
                    $data['datasets'][1]['data'][]  = $sms_per_week[$date]['gross'];
                } else {
                    $data['datasets'][0]['data'][] = 0;
                    $data['datasets'][1]['data'][] = 0;
                }

                if(isset($failed_per_week[$date]) && !empty($failed_per_week[$date])) {
                    $data['datasets'][2]['data'][] = $failed_per_week[$date];
                } else {
                    $data['datasets'][2]['data'][] = 0;
                }

                if(in_array($date, $refill_dates)) {
                    $data['datasets'][3]['data'][] = $highest_value;
                } else {
                    $data['datasets'][3]['data'][] = 0;
                }
            }

        } elseif($days <= 366) {

            /*
             * 1 Year, month formatting, everything under 1 year and above 6 months
             */
            $sms_per_month    = [];
            $failed_per_month = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->copy()->format('M Y');
            }

            /**  Getting sent SMS per month **/
            foreach ($sms as $item){
                $date = $item->sent_at->copy()->format('M Y');

                if(isset($sms_per_month[$date])) {
                    $sms_per_month[$date]['net']      += 1;
                    $sms_per_month[$date]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_month[$date]['net']      = 1;
                    $sms_per_month[$date]['gross']    = (1 * $item->quantity);
                }

                if($sms_per_month[$date]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_month[$date]['gross'];
                }
            }

            /** Getting failed SMS per month **/
            foreach ($receipts as $receipt){
                $date = Carbon::parse($receipt->message_timestamp)->copy()->format('M Y');

                if(isset($failed_per_month[$date])) {
                    $failed_per_month[$date]  += 1;
                } else {
                    $failed_per_month[$date]  = 1;
                }

                if($failed_per_month[$date] >= $highest_value) {
                    $highest_value = $failed_per_month[$date];
                }
            }

            for($i = 0; $i <= $months ; $i++){
                $date               = Carbon::parse($date_array[0])->copy()->addMonth($i)->format('M Y');
                $data['labels'][]   = $date;

                if(isset($sms_per_month[$date]) && !empty($sms_per_month[$date])) {
                    $data['datasets'][0]['data'][]  = $sms_per_month[$date]['net'];
                    $data['datasets'][1]['data'][]  = $sms_per_month[$date]['gross'];
                } else {
                    $data['datasets'][0]['data'][] = 0;
                    $data['datasets'][1]['data'][] = 0;
                }

                if(isset($failed_per_month[$date]) && !empty($failed_per_month[$date])) {
                    $data['datasets'][2]['data'][] = $failed_per_month[$date];
                } else {
                    $data['datasets'][2]['data'][] = 0;
                }

                if(in_array($date, $refill_dates)) {
                    $data['datasets'][3]['data'][] = $highest_value;
                } else {
                    $data['datasets'][3]['data'][] = 0;
                }
            }

        } elseif($days <= 1100) {

            /*
             * 1-3 Years, quarter formatting, everything above 1 year and less than 3 years
             */
            $sms_per_quarter    = [];
            $failed_per_quarter = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = 'Q'.$refill->created_at->copy()->quarter.' '.$refill->created_at->copy()->format('Y');
            }

            /**  Getting sent SMS per quarter **/
            foreach ($sms as $item){
                $date = 'Q'.$item->sent_at->copy()->quarter.' '.$item->sent_at->copy()->format('Y');

                if(isset($sms_per_quarter[$date])) {
                    $sms_per_quarter[$date]['net']      += 1;
                    $sms_per_quarter[$date]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_quarter[$date]['net']      = 1;
                    $sms_per_quarter[$date]['gross']    = (1 * $item->quantity);
                }

                if($sms_per_quarter[$date]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_quarter[$date]['gross'];
                }
            }

            /** Getting failed SMS per quarter **/
            foreach ($receipts as $receipt){
                $date = 'Q'.Carbon::parse($receipt->message_timestamp)->copy()->quarter.' '.Carbon::parse($receipt->message_timestamp)->copy()->format('Y');

                if(isset($failed_per_quarter[$date])) {
                    $failed_per_quarter[$date]  += 1;
                } else {
                    $failed_per_quarter[$date]  = 1;
                }

                if($failed_per_quarter[$date] >= $highest_value) {
                    $highest_value = $failed_per_quarter[$date];
                }
            }

            for($i = 0; $i <= $quarters ; $i++){
                $date               = 'Q'.Carbon::parse($date_array[0])->copy()->addQuarter($i)->quarter.' '.Carbon::parse($date_array[0])->copy()->addQuarter($i)->format('Y');
                $data['labels'][]   = $date;

                if(isset($sms_per_quarter[$date]) && !empty($sms_per_quarter[$date])) {
                    $data['datasets'][0]['data'][]  = $sms_per_quarter[$date]['net'];
                    $data['datasets'][1]['data'][]  = $sms_per_quarter[$date]['gross'];
                } else {
                    $data['datasets'][0]['data'][] = 0;
                    $data['datasets'][1]['data'][] = 0;
                }

                if(isset($failed_per_quarter[$date]) && !empty($failed_per_quarter[$date])) {
                    $data['datasets'][2]['data'][] = $failed_per_quarter[$date];
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

            /*
             * 3+ Years, year formatting, everything above 3 years
             */
            $sms_per_year    = [];
            $failed_per_year = [];

            /** Formatting refill dates **/
            foreach($refills as $refill){
                $refill_dates[] = $refill->created_at->copy()->format('Y');
            }

            /**  Getting sent SMS per year **/
            foreach ($sms as $item){
                $date = $item->sent_at->copy()->format('Y');

                if(isset($sms_per_year[$date])) {
                    $sms_per_year[$date]['net']      += 1;
                    $sms_per_year[$date]['gross']    += (1 * $item->quantity);
                } else {
                    $sms_per_year[$date]['net']      = 1;
                    $sms_per_year[$date]['gross']    = (1 * $item->quantity);
                }

                if($sms_per_year[$date]['gross'] >= $highest_value) {
                    $highest_value = $sms_per_year[$date]['gross'];
                }
            }

            /** Getting failed SMS per year **/
            foreach ($receipts as $receipt){
                $date = Carbon::parse($receipt->message_timestamp)->copy()->format('Y');

                if(isset($failed_per_year[$date])) {
                    $failed_per_year[$date]  += 1;
                } else {
                    $failed_per_year[$date]  = 1;
                }

                if($failed_per_year[$date] >= $highest_value) {
                    $highest_value = $failed_per_year[$date];
                }
            }

            for($i = 0; $i <= $years ; $i++){
                $date               = Carbon::parse($date_array[0])->copy()->addYear($i)->format('Y');
                $data['labels'][]   = $date;

                if(isset($sms_per_year[$date]) && !empty($sms_per_year[$date])) {
                    $data['datasets'][0]['data'][]  = $sms_per_year[$date]['net'];
                    $data['datasets'][1]['data'][]  = $sms_per_year[$date]['gross'];
                } else {
                    $data['datasets'][0]['data'][] = 0;
                    $data['datasets'][1]['data'][] = 0;
                }

                if(isset($failed_per_year[$date]) && !empty($failed_per_year[$date])) {
                    $data['datasets'][2]['data'][] = $failed_per_year[$date];
                } else {
                    $data['datasets'][2]['data'][] = 0;
                }

                if(in_array($date, $refill_dates)) {
                    $data['datasets'][3]['data'][] = $highest_value;
                } else {
                    $data['datasets'][3]['data'][] = 0;
                }
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
