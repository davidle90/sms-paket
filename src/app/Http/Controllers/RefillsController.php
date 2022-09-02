<?php namespace Rocketlabs\Sms\App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Rocketlabs\Sms\App\Models\Refills;

class RefillsController extends Controller
{

	public function index(Request $request)
	{
        $refills            = $this->filter($request, false);
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


       return view('rl_sms::admin.pages.refills.index', [
           'refills'            => $refills,
           'filter_array'       => $filter_array,
           'starts_at'          => $starts_at,
           'ends_at'            => $ends_at,
       ]);

	}

    public function filter(Request $request, $ajax = true)
    {
        $filter = [
            'daterange'             => $request->get('daterange', $request->session()->get('refills_filter.daterange', null)),
            'daterange_full'        => $request->get('daterange_full', $request->session()->get('refills_filter.daterange_full', null))
        ];

        /*
         * Initialize the query for grabbing information
         */
        $refillsQuery = Refills::query();

        /*
         * Filter by daterange
         */
        if(isset($filter['daterange_full']) && !empty($filter['daterange_full'])){
            $date_array = explode(' - ', $filter['daterange_full']);

            $refillsQuery->whereDate('created_at', '>=', $date_array[0]);
            $refillsQuery->whereDate('created_at', '<=', $date_array[1]);

            $request->session()->put('refills_filter.daterange', $filter['daterange']);
            $request->session()->put('refills_filter.daterange_full', $filter['daterange_full']);

        } elseif(isset($filter['daterange']) && !empty($filter['daterange'])){
            $date_array = explode(' - ', $filter['daterange']);

            $refillsQuery->whereDate('created_at', '>=', $date_array[0]);
            $refillsQuery->whereDate('created_at', '<=', $date_array[1]);

            $request->session()->put('refills_filter.daterange', $filter['daterange']);
            $request->session()->forget('refills_filter.daterange_full');
        } else{
            $request->session()->forget('refills_filter.daterange');
            $request->session()->forget('refills_filter.daterange_full');

            $refillsQuery->whereDate('created_at', '>=', now()->startOfMonth()->format('Y-m-d'));
            $refillsQuery->whereDate('created_at', '<=', now()->endOfMonth()->format('Y-m-d'));
        }

        $refillsQuery->orderBy('created_at', 'desc');

        $refills = $refillsQuery->paginate(50);
        $refills->withPath(route('rl_sms.admin.refills.index'));

        /*
         * Returns the result as a JSON so the site can read the information
         */
        if($request->wantsJson()){

            $response['view'] = view('rl_sms::admin.pages.refills.includes.table', [
                'refills' => $refills
            ])->render();

            return response()->json($response);
        }else{
            return $refills;
        }
    }

    public function clear_filter()
    {
        session()->forget('refills_filter');

        $response = [
            'status' => 1
        ];

        return response()->json($response);
    }
}
