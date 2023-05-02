<?php namespace Rocketlabs\Sms\App\Jobs;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Rocketlabs\Sms\App\Classes\Api\SmsServerApi;
use Rocketlabs\Sms\App\Events\SmsSent;
use Rocketlabs\Sms\App\Models\Sms;
use Rocketlabs\Sms\App\Models\NexmoResponses;

use DB;
use Vonage;
use rl_sms;

class SendSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 1;

    protected $sender;
    protected $receiver;
    protected $message;
    protected $message_id;
    protected $priority_slug;

    public function __construct($sender, $receiver, $message, $message_id, $priority_slug)
    {
        $this->sender           = $sender;
        $this->receiver         = $receiver;
        $this->message          = $message;
        $this->message_id       = $message_id;
        $this->priority_slug    = $priority_slug;
    }

    public function handle()
    {
        try {

            if(!empty($this->receiver['phone'])){
                if($this->receiver['phone'][0] == '+'){
                    $phone_number = PhoneNumber::make($this->receiver['phone'])->formatE164();
                } else {
                    $phone_number = PhoneNumber::make($this->receiver['phone'], 'SE')->formatE164();
                }

                $phone_country_code     = PhoneNumber::make($phone_number)->getCountry();
                $smsbox_country_codes   = config('rl_sms.api.country_codes');

                if(in_array($phone_country_code, $smsbox_country_codes)) {
                    $sms_server_api = new SmsServerApi(config('rl_sms.api.live'));
                    $server_status  = $sms_server_api->getServerStatus();

                    if($server_status == 1) {
                        $sms_priority = config('rl_sms.models.priorities')::where('slug', $this->priority_slug)->first();
                        $sms_server_api->sendSms($this->receiver['name'], $this->receiver['phone'], $this->sender->sms_label, $this->message, $this->message_id, $sms_priority->priority, $this->priority_slug);
                    } else {
                        $this->send_via_vonage($phone_number);
                    }
                } else {
                    $this->send_via_vonage($phone_number);
                }
            }

        } catch (\Exception $e) {

            throw $e;

        }
    }

    private function send_via_vonage($phone_number)
    {
        $vonage_sms = [
            'to'   => str_replace('+', '', $phone_number),
            'from' => $this->sender->sms_label,
            'text' => $this->message,
        ];

        $response_vonage = Vonage::sms()->send(new Vonage\SMS\Message\SMS(
            $vonage_sms['to'],
            $vonage_sms['from'],
            $vonage_sms['text'],
            'text'
        ));

        rl_sms::store_sms_and_response($response_vonage, $this->message_id, $this->sender->sms_label, $this->receiver['name'], $this->receiver['phone']);
    }

}
