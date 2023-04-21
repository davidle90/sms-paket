<?php

namespace Rocketlabs\Sms\App\Http\Controllers\Api;

use Rocketlabs\Sms\App\Models\Queue;

class SmsController extends ResponseController
{
    public function getServerStatus()
    {
        // check smsbox status

        $smsbox_status = 1;

        return response()->json(['server_status' => $smsbox_status], 200);
    }

    public function sendSms()
    {
        $input = [
            'to'            => request()->get('to'),
            'from'          => request()->get('from'),
            'message'       => request()->get('message'),
            'priority_slug' => request()->get('priority_slug')
        ];

        $new_sms = Queue::create([
            'message_id'        => $input['message_id'],
            'priority'          => $input['priority_slug'],
            'sender_title'      => $input['sender_title'],
            'receiver_title'    => $input['receiver_title'],
            'receiver_phone'    => $input['receiver_phone'],
            'country'           => $input['country'],
            'quantity'          => $input['quantity'],
        ]);

        return response()->json($input);
    }
}