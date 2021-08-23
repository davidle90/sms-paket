<?php namespace Rocketlabs\Sms\App\Classes;

use Illuminate\Support\Collection;
use Illuminate\Support\Collection as BaseCollection;

/*
 * Helpers
 */

use Illuminate\Support\Facades\Config;
use Nexmo\Laravel\Facade\Nexmo;
use Propaganistas\LaravelPhone\PhoneNumber;
use Rocketlabs\Sms\App\Jobs\SendSms;
use Rocketlabs\Sms\App\Models\Messages;
use Rocketlabs\Sms\App\Models\NexmoResponses;
use Rocketlabs\Sms\App\Models\Senders;
use Rocketlabs\Sms\App\Models\Sms;

class Helpers
{

    /*
     * Forms models
     */
    public function sms_model()
    {
        return config('rl_sms.models.sms');
    }

    public function messages_model()
    {
        return config('rl_sms.models.messages');
    }

    public function send($sender_id, $receivers, $message)
    {
        $sender         = Senders::find($sender_id);
        $number_keys    = [];

        $new_message = $this->store_message($message);

        foreach ($receivers as $receiver) {
            if(isset($number_keys[$receiver['phone']])) continue;
            $number_keys[$receiver['phone']] = true;

            $receiver_name      = explode(' ', $receiver['name']);
            $message_formatted  = str_replace('%firstname%', trim($receiver_name[0] ?? ''), $message);
            $message_formatted  = str_replace('%lastname%', trim($receiver_name[1] ?? ''), $message_formatted);

            SendSms::dispatch($sender, $receiver, $message_formatted, $new_message->id);

            //$response = Nexmo::message()->send([
            //    'to'   => str_replace('+', '', PhoneNumber::make($receiver['phone'])->formatE164()),
            //    'from' => $sender->sms_label,
            //    'text' => $message_formatted,
            //]);
            //
            //pre($response['messages']);
        }
    }

    public function store_message($message)
    {
        $new_message = new Messages();
        $new_message->text      = $message;
        $new_message->sha256    = sha1($message);
        $new_message->save();

        return $new_message;
    }

    public function store_sms_and_response($response, $message_id, $sender_title, $receiver_name, $receiver_phone)
    {
        $new_sms = new Sms();
        $new_sms->message_id        = $message_id;
        $new_sms->sender_title      = $sender_title;
        $new_sms->receiver_title    = $receiver_name;
        $new_sms->receiver_phone    = $receiver_phone;
        $new_sms->country           = strtolower(PhoneNumber::make($receiver_phone)->getCountry());
        $new_sms->quantity          = $response['message-count'];
        $new_sms->sent_at           = now();
        $new_sms->save();

        foreach ($response['messages'] as $data) {
            $new_nexmo_response = new NexmoResponses();
            $new_nexmo_response->sms_id         = $new_sms->id;
            $new_nexmo_response->message_id     = $data['message-id'];
            $new_nexmo_response->status         = $data['status'];
            $new_nexmo_response->to             = $data['to'];
            $new_nexmo_response->balance        = $data['remaining-balance'];
            $new_nexmo_response->price          = $data['message-price'];
            $new_nexmo_response->network        = $data['network'];
            $new_nexmo_response->save();
        }
    }

}


