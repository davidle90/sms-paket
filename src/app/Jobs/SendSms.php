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

        //DB::beginTransaction();

        try {

            if(!empty($this->receiver['phone'])){
                $response = Nexmo::message()->send([
                    'to'   => str_replace('+', '', PhoneNumber::make($this->receiver['phone'])->formatE164()),
                    'from' => $this->sender->sms_label,
                    'text' => $this->message,
                ]);
            }

            if(isset($response)) {
                $new_sms = new Sms();
                $new_sms->message_id        = $this->message_id;
                $new_sms->sender_title      = $this->sender->sms_label;
                $new_sms->receiver_title    = $this->receiver['name'];
                $new_sms->receiver_phone    = $this->receiver['phone'];
                $new_sms->country           = strtolower(PhoneNumber::make($this->receiver['phone'])->getCountry());
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

            //DB::commit();

        } catch (\Exception $e) {

            //DB::rollback();
            pre($e->getMessage());

            throw $e;

        }
    }

}