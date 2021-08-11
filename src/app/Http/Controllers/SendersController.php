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

    public function store()
    {
        return '';
    }

}