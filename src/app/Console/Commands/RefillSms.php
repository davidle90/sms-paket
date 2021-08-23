<?php namespace Rocketlabs\Sms\App\Console\Commands;

use Illuminate\Console\Command;
use Rocketlabs\Groups\App\Models\Groups;
use Rocketlabs\Notifications\App\Notifications\Notifier;
use Notification;
use rl_settings;
use Rocketlabs\Sms\App\Models\Refills;
use Rocketlabs\Sms\App\Models\Sms;

class RefillSms extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sms:refill';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refills SMS if threshold is passed.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {

        try {

            $latest_refill = Refills::orderBy('created_at', 'desc')->first();

            $incoming_and_outgoing_messages_group_id = rl_settings::get(config('rl_settings.settings_source'),'incoming_and_outgoing_messages_group_id');
            $group = Groups::with('team.members.user')->find($incoming_and_outgoing_messages_group_id);

            if(!empty($latest_refill)) {
                $sms_sent = Sms::where('sent_at', '>=', $latest_refill->created_at)->sum('quantity');
                $sms_left = $latest_refill->quantity - $sms_sent;
            } else {
                $sms_sent = Sms::sum('quantity');
                $sms_left = -$sms_sent;
            }

            if($sms_left <= config('rl_sms.refill.threshold')) {
                $refill_count = 0;

                while ($sms_left < config('rl_sms.refill.amount')) {
                    $refill_count++;
                    $sms_left += config('rl_sms.refill.amount');
                }

                $new_refill = new Refills();
                $new_refill->quantity = $refill_count * config('rl_sms.refill.amount');
                $new_refill->remains  = $sms_left - ($refill_count * config('rl_sms.refill.amount'));
                $new_refill->count    = $refill_count;
                $new_refill->save();

                if(!empty($group)) {
                    foreach($group->team->members as $recipient){
                        $recipient->user->notify(new Notifier('sms_refilled', [
                            'id'                => $new_refill->id,
                            'name'              => $recipient->user->firstname,
                            'link'              => route('rl_sms.admin.sms.index'),
                            'refill.quantity'   => $new_refill->quantity,
                        ]));
                    }
                }

                $notifier = new Notifier('sms_refilled', [
                    'id'                => $new_refill->id,
                    'name'              => '',
                    'link'              => route('rl_sms.admin.sms.index'),
                    'refill.quantity'   => $new_refill->quantity
                ]);

                $notifier->explicit_channels = ['mail'];

                Notification::route('mail', config('rl_sms.email.webmaster'))
                    ->notify($notifier);
            }

        } catch (\Exception $e) {

            throw $e;

        }

    }
}

