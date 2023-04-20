<?php

namespace Rocketlabs\Sms\App\Http\Controllers\Api;

class SmsController extends ResponseController
{
    public function test()
    {
        return response()->json(['test' => 'test'], 200);
    }

    public function getServerStatus()
    {
        return response()->json(['server_status' => 1], 200);
    }

    public function getSms()
    {
    }

    public function sendSms()
    {
    }
}