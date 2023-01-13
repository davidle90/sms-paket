<?php namespace Rocketlabs\Sms\App\Channels;

use Illuminate\Notifications\Notification;
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
            $vonage_sms = [
                'to'   => str_replace('+', '', $to),
                'from' => $from,
                'text' => $message,
            ];

            $response_vonage = Vonage::sms()->send(new Vonage\SMS\Message\SMS(
                $vonage_sms['to'],
                $vonage_sms['from'],
                $vonage_sms['text']
            ));
        }

        if(isset($response_vonage)) {
            rl_sms::store_sms_and_response($response_vonage, $new_message->id, $from, $response['variables']['receiver'] ?? '', $to, $response['variables']);

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
