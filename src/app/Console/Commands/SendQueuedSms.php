<?php namespace Rocketlabs\Sms\App\Console\Commands;

use Illuminate\Console\Command;
use Rocketlabs\Sms\App\Jobs\SendQueuedSms as SendQueuedSmsJob;

class SendQueuedSms extends Command
{

    protected $signature = 'sms:send_queued_sms';
    protected $description = 'Send queued sms to sms box';
    private $sms_queue_model;

    public function __construct()
    {
        parent::__construct();
        $this->sms_queue_model = config('rl_sms.models.queue');
    }

    public function handle()
    {
        $this->sms_queue_model::orderBy('priority', 'asc')->chunk(100, function($sms_batch) {
            SendQueuedSmsJob::dispatch($sms_batch);
        });

    }

}
