<?php namespace Rocketlabs\Sms\App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Rocketlabs\Sms\App\Classes\Api\SmsServerApi;

class SendQueuedSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $sms_jobs;

    public function __construct($sms_jobs)
    {
        $this->sms_jobs = $sms_jobs;
    }

    public function handle()
    {
        $sms_server_api = new SmsServerApi();
        $server_status = $sms_server_api->getServerStatus();

        if($server_status == 1){
            pre($this->sms_jobs->toArray(), false);
        } else {
            pre('send via vonage');
        }

    }
}
