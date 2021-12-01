<?php namespace Rocketlabs\Sms\App\Channels;

use Illuminate\Notifications\Notification;
use Nexmo\Laravel\Facade\Nexmo;
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

        unset($response['variables']['attachments']);
        unset($response['variables']['email_listener']);
        unset($response['variables']['pb_site_id']);

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
            $response_nexmo = Nexmo::message()->send([
                'to'   => $to,
                'from' => $from,
                'text' => $message,
            ]);
        }

        if(isset($response_nexmo)) {
            rl_sms::store_sms_and_response($response_nexmo, $new_message->id, $from, $response['variables']['receiver'] ?? '', $to, $response['variables']);
        }

    }
}
