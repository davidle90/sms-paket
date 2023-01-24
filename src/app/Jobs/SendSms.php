<?php namespace Rocketlabs\Sms\App\Jobs;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

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

    public function __construct($sender, $receiver, $message, $message_id)
    {
        $this->sender       = $sender;
        $this->receiver     = $receiver;
        $this->message      = $message;
        $this->message_id   = $message_id;
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

            }

            if(isset($response_vonage)) {
                rl_sms::store_sms_and_response($response_vonage, $this->message_id, $this->sender->sms_label, $this->receiver['name'], $this->receiver['phone']);
            }

        } catch (\Exception $e) {

            throw $e;

        }
    }

}
