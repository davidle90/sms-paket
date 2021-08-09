<?php namespace Rocketlabs\Sms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;

use DB;
use Rocketlabs\Languages\App\Models\Languages;
use Rocketlabs\Sms\App\Models\Sms;
use Validator;
use Carbon\Carbon;

class SmsController extends Controller
{

	public function index(Request $request)
	{
        $sms = $this->filter($request, false);

        $first_sms  = Sms::orderBy('sent_at', 'asc')->first();
        $last_sms   = Sms::orderBy('sent_at', 'desc')->first();

        $filter_array['timeline'] = [
            'Alla' => [
                date('Y-m-d', $first_sms->sent_at->timestamp),
                date('Y-m-d', $last_sms->sent_at->timestamp),
            ],
            date('F') => [
                date('Y-n-1'),
                date('Y-n-t'),
            ],
            date('F', strtotime('last month')) => [
                date('Y-n-j', strtotime("first day of previous month")),
                date('Y-n-j', strtotime("last day of previous month")),
            ],
            'År ' . date('Y') => [
                date('Y-01-01'),
                date('Y-12-31'),
            ],
            'År ' . date('Y',strtotime("-1 year")) => [
                date('Y-01-01',strtotime("-1 year")),
                date('Y-12-31',strtotime("-1 year")),
            ],
            'R12' => [
                date('Y-m-1',strtotime("-1 year")),
                date('Y-m-t'),
            ]
        ];

       return view('rl_sms::admin.pages.sms.index', [
           'sms'            => $sms,
           'filter_array'   => $filter_array,
           'first_sms'  => $first_sms,
           'last_sms'   => $last_sms
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
        $starts_at  = $request->get('starts_at', '1900-01-01 00:00:00');
        $ends_at    = $request->get('ends_at', '2100-01-01 00:00:00');

        $sms = Sms::orderBy('sent_at', 'asc')
            ->where('sent_at', '>=', $starts_at)
            ->where('sent_at', '<=', $ends_at)
            ->get();

        $data        = [
            'labels'    => [],
            'datasets'  => [
                [
                    'label'             => 'Skickade SMS (netto)',
                    'data'              => [],
                    'backgroundColor'   => '#20a8d8',
                    'borderColor'       => '#20a8d8',
                    'borderWidth'       => 1
                ],
                [
                    'label'             => 'Skickade SMS (brutto)',
                    'data'              => [],
                    'backgroundColor'   => '#ff5454',
                    'borderColor'       => '#ff5454',
                    'borderWidth'       => 1
                ]
            ]
        ];

        $sms_per_day = [];
        
        /**  Getting sent SMS per day **/
        foreach ($sms as $item){
            $date = $item->sent_at->toDateString();
            
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


        return response()->json($data);
    }

}