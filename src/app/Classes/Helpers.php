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
use Rocketlabs\Sms\App\Models\Refills;
use Rocketlabs\Sms\App\Models\Senders;
use Rocketlabs\Sms\App\Models\Sms;

class Helpers
{
    const VERIFY_SUCCESS        = 'SUCCESS';
    const VERIFY_IN_PROGRESS    = 'IN PROGRESS';
    const VERIFY_FAILED         = 'FAILED';
    const VERIFY_EXPIRED        = 'EXPIRED';
    const VERIFY_CANCELLED      = 'CANCELLED';

    public function getVerifyStatusCode($status_string)
    {
        $verify_code = null;

        switch ($status_string) {
            case self::VERIFY_SUCCESS:
                $verify_code = 1;
                break;
            case self::VERIFY_IN_PROGRESS:
                $verify_code = 2;
                break;
            case self::VERIFY_FAILED:
                $verify_code = 3;
                break;
            case self::VERIFY_EXPIRED:
                $verify_code = 4;
                break;
            case self::VERIFY_CANCELLED:
                $verify_code = 5;
                break;
            default:
                $verify_code = 1337;
        }

        return $verify_code;
    }

    public function getVerifyStatusString($status_code)
    {
        $verify_string = null;

        switch ($status_code) {
            case 1:
                $verify_string = self::VERIFY_SUCCESS;
                break;
            case 2:
                $verify_string = self::VERIFY_IN_PROGRESS;
                break;
            case 3:
                $verify_string = self::VERIFY_FAILED;
                break;
            case 4:
                $verify_string = self::VERIFY_EXPIRED;
                break;
            case 5:
                $verify_string = self::VERIFY_CANCELLED;
                break;
            default:
                $verify_string = '1337';
        }

        return $verify_string;
    }

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

    public function get_last_refill()
    {
        $last_refill = Refills::orderBy('created_at', 'desc')->first();
        if(!isset($last_refill) || empty($last_refill)){
            return null;
        }
        return $last_refill;
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

    public function store_sms_and_response($response, $message_id, $sender_title, $receiver_name, $receiver_phone, $variables = null, $verify_search = 0)
    {
        $last_refill    = rl_sms::get_last_refill();
        $sms_unit_price = config('rl_sms.price');
        $vat_rate       = config('rl_sms.vat_rate');

        if(isset($last_refill)){

            // Set payed unit price from last purchase
            if(!is_null($last_refill->sms_unit_price)){
                $sms_unit_price = $last_refill->sms_unit_price;
            }

            // Set vat rate from last purchase
            if(!is_null($last_refill->vat_rate)){
                $sms_vat_rate   = $last_refill->sms_unit_price;
            }
        }

        $new_sms = new Sms();
        $new_sms->message_id        = $message_id;
        $new_sms->sender_title      = $sender_title;
        $new_sms->receiver_title    = $receiver_name;
        $new_sms->receiver_phone    = $receiver_phone;
        try {
            $new_sms->country           = strtolower(PhoneNumber::make($receiver_phone)->getCountry());
        } catch(\Exception){
            $new_sms->country = null;
        }
        $new_sms->variables         = $variables;
        $new_sms->quantity          = $response['message-count'];
        $new_sms->sent_at           = now();

        $price_sms_excl_vat         = $sms_unit_price; // Price of 1 sms excl vat
        $vat_dividor                = ($vat_rate / 100)+1;
        $price_excl_vat             = $price_sms_excl_vat*$new_sms->quantity;
        $price_incl_vat             = number_format($price_excl_vat * $vat_dividor, 2, '.', '');
        $price_vat_total            = number_format($price_incl_vat-$price_excl_vat, 2, '.', '');

        $new_sms->vat_rate                 = $vat_rate;
        $new_sms->price_incl_vat           = $price_incl_vat;
        $new_sms->price_excl_vat           = $price_excl_vat;
        $new_sms->price_vat                = $price_vat_total;

        $new_sms->save();

        foreach ($response['messages'] as $data) {
            $new_nexmo_response = new NexmoResponses();
            $new_nexmo_response->sms_id         = $new_sms->id;
            $new_nexmo_response->message_id     = $data['message-id'];
            $new_nexmo_response->request_id     = $data['request_id'] ?? null;
            $new_nexmo_response->status         = $data['status'];
            $new_nexmo_response->to             = $data['to'];
            $new_nexmo_response->balance        = $data['remaining-balance'];
            $new_nexmo_response->price          = $data['message-price'];
            $new_nexmo_response->network        = $data['network'];
            $new_nexmo_response->verify_search  = $verify_search;
            $new_nexmo_response->save();
        }
    }

}


