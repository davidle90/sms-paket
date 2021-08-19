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
use Rocketlabs\Sms\App\Models\Senders;

class Helpers
{

    /*
     * Forms models
     */
    public function forms_model()
    {
        return config('rl_sms.models.sms');
    }

    public function send($sender_id, $receivers, $message)
    {
        $sender         = Senders::find($sender_id);
        $number_keys    = [];

        $new_message = new Messages();
        $new_message->text = $message;
        $new_message->save();

        foreach ($receivers as $receiver) {
            if(isset($number_keys[$receiver['phone']])) continue;
            $number_keys[$receiver['phone']] = true;

            $receiver_name      = explode(' ', $receiver['name']);
            $message_formatted  = str_replace('%firstname%', trim($receiver_name[0]) ?? '', $message);
            $message_formatted  = str_replace('%lastname%', trim($receiver_name[1]) ?? '', $message_formatted);

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

}


