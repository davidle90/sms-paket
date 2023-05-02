<?php

namespace Rocketlabs\Sms\App\Http\Controllers\Api;

use Propaganistas\LaravelPhone\PhoneNumber;
use Rocketlabs\Sms\App\Models\Queue;
use Rocketlabs\Sms\App\Models\ServerStatus;

class SmsController extends ResponseController
{
    public function getServerStatus()
    {
        $smsbox_status = ServerStatus::orderBy('created_at', 'desc')->first();

        return response()->json(['server_status' => $smsbox_status->status], 200);
    }

    public function sendSms()
    {
        $input = [
            'receiver_name'     => request()->get('receiver_name'),
            'receiver_phone'    => request()->get('receiver_phone'),
            'sender'            => request()->get('sender'),
            'message'           => request()->get('message'),
            'message_id'        => request()->get('message_id'),
            'priority'          => request()->get('priority'),
            'priority_slug'     => request()->get('priority_slug')
        ];

        if($input['receiver_phone'][0] == '+'){
            $phone_number = PhoneNumber::make($input['receiver_phone'])->formatE164();
        } else {
            $phone_number = PhoneNumber::make($input['receiver_phone'], 'SE')->formatE164();
        }

        $phone_country_code = PhoneNumber::make($phone_number)->getCountry();

        $new_sms = new Queue();
        $new_sms->message_id        = $input['message_id'];
        $new_sms->priority          = $input['priority'];
        $new_sms->priority_slug     = $input['priority_slug'];
        $new_sms->sender_title      = $input['sender'];
        $new_sms->receiver_title    = $input['receiver_name'];
        $new_sms->receiver_phone    = $input['receiver_phone'];
        $new_sms->country           = $phone_country_code;
        $new_sms->sent_at           = now();

        $new_sms->save();

        return response()->json($input);
    }
}