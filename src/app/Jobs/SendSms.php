<?php namespace Rocketlabs\Sms\App\Jobs;

use Propaganistas\LaravelPhone\PhoneNumber;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use DB;
use Nexmo\Laravel\Facade\Nexmo;
use Rocketlabs\Sms\App\Models\Sms;
use Rocketlabs\Sms\App\Models\NexmoResponses;
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
                $response = Nexmo::message()->send([
                    'to'   => str_replace('+', '', PhoneNumber::make($this->receiver['phone'])->formatE164()),
                    'from' => $this->sender->sms_label,
                    'text' => $this->message,
                ]);
            }

            if(isset($response)) {
                rl_sms::store_sms_and_response($response, $this->message_id, $this->sender->sms_label, $this->receiver['name'], $this->receiver['phone']);
            }

        } catch (\Exception $e) {

            throw $e;

        }
    }

}