<?php namespace Rocketlabs\Sms\App\Channels;

use Illuminate\Notifications\Notification;
use Propaganistas\LaravelPhone\PhoneNumber;
use Rocketlabs\Sms\App\Classes\Api\SmsServerApi;
use Rocketlabs\Sms\App\Events\SmsSent;
use Vonage;
use rl_sms;

class SmsChannel
{
    /**
     * Send the given notification.
     *
     * @param  mixed  $notifiable
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return void
     */
    public function send($notifiable, Notification $notification)
    {
        $response = $notification->toSms($notifiable);

        $from           = $response['from'];
        $message        = $response['message'];
        $event_ref      = $response['variables']['event_ref'] ?? null;
        $priority_slug  = $response['priority'];

        unset($response['variables']['attachments']);
        unset($response['variables']['email_listener']);
        unset($response['variables']['pb_site_id']);
        unset($response['variables']['event_ref']);

        $sha256         = sha1($message);
        $new_message    = rl_sms::messages_model()::where('sha256', $sha256)->first();

        if(empty($new_message)) {
            $new_message = rl_sms::store_message($message);
        }

        foreach($response['variables'] as $key => $value){
            if(is_null($value) || (!is_string($value) && !is_numeric($value))){
                continue;
            }
            $message    = str_replace('%'.$key.'%', $value, $message);
        }

        $to = $notifiable->sms ?? $notifiable->routes['sms'] ?? '';

        if(!empty($to)){
            if($to[0] == '+'){
                $phone_number = PhoneNumber::make($to)->formatE164();
            } else {
                $phone_number = PhoneNumber::make($to, 'SE')->formatE164();
            }

            $phone_country_code     = PhoneNumber::make($phone_number)->getCountry();
            $smsbox_country_codes   = config('rl_sms.api.country_codes');

            if(in_array($phone_country_code, $smsbox_country_codes)){
                $sms_server_api = new SmsServerApi(config('rl_sms.api.live'));
                $server_status  = $sms_server_api->getServerStatus();

                if($server_status == 1){
                    $sms_priority = config('rl_sms.models.priorities')::where('slug', $priority_slug)->first();

                    $sms_server_api->sendSms($response['variables']['receiver'] ?? '', $phone_number, $from, $message, $new_message->id, $sms_priority->priority, $priority_slug);
                } else {
                    $this->send_via_vonage($to, $from, $message, $new_message->id, $response, $event_ref);
                }
            } else {
                $this->send_via_vonage($to, $from, $message, $new_message->id, $response, $event_ref);
            }
        }
    }

    private function send_via_vonage($to, $from, $message, $new_message_id, $response, $event_ref)
    {
        $vonage_sms = [
            'to'   => str_replace('+', '', $to),
            'from' => $from,
            'text' => $message,
        ];

        $response_vonage = Vonage::sms()->send(new Vonage\SMS\Message\SMS(
            $vonage_sms['to'],
            $vonage_sms['from'],
            $vonage_sms['text'],
            'text'
        ));

        if(isset($response_vonage)) {
            rl_sms::store_sms_and_response($response_vonage, $new_message_id, $from, $response['variables']['receiver'] ?? '', $to, $response['variables']);

            // Triggers SmsSent event if event reference is present
            if(!empty($event_ref)) {
                event(new SmsSent($event_ref, [
                    'to'    => $vonage_sms['to'],
                    'from'  => $vonage_sms['from'],
                    'text'  => $vonage_sms['text']
                ]));
            }
        }
    }
}
