<?php namespace Rocketlabs\Sms\App\Console\Commands;

use Illuminate\Console\Command;
use Rocketlabs\Groups\App\Models\Groups;
use Rocketlabs\Notifications\App\Notifications\Notifier;
use Notification;
use rl_settings;
use Rocketlabs\Sms\App\Models\Refills;
use Rocketlabs\Sms\App\Models\Sms;

use rl_sms;

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

        $sms_refill_amount      = config('rl_sms.refill.amount');
        $sms_threshold          = config('rl_sms.refill.threshold');
        $sms_unit_price         = config('rl_sms.price');
        $sms_unit_price_last    = $sms_unit_price;
        $vat_rate               = config('rl_sms.vat_rate');
        $sms_remaining          = 0; // Total sms left that can be sent
        $sms_remaining_sum      = 0; // Total amount (kr) that we have left to use for sending sms

        try {

            $last_refill = rl_sms::get_last_refill();

            $incoming_and_outgoing_messages_group_id = rl_settings::get(config('rl_settings.settings_source'),'incoming_and_outgoing_messages_group_id');
            $group = Groups::with('team.members.user')->find($incoming_and_outgoing_messages_group_id);

            if(!empty($last_refill)) {

                $sms_sent       = Sms::where('sent_at', '>=', $last_refill->created_at)->sum('quantity');
                $sms_remaining  = ($last_refill->quantity + $last_refill->remains) - $sms_sent;

                if(isset($last_refill->sms_unit_price) && !is_null($last_refill->sms_unit_price)){
                    $sms_unit_price_last = $last_refill->sms_unit_price;
                }

            } else {
                $sms_sent = Sms::sum('quantity');
                $sms_remaining = -$sms_sent;
            }

            if($sms_remaining <= $sms_threshold) {
                $refill_count = 0;

                $sms_remaining_sum = $sms_remaining / $sms_unit_price_last; // divide remaining sms with last payed price per sms

                while ($sms_remaining_sum < $sms_refill_amount) {
                    $refill_count++;
                    $sms_remaining_sum += $sms_refill_amount;
                }

                $sms_refill_sum     = $refill_count * $sms_refill_amount;
                $refill_quantity    = floor($sms_refill_sum / $sms_unit_price);

                $new_refill = new Refills();
                $new_refill->quantity       = $refill_quantity;
                $new_refill->remains        = $sms_remaining;
                $new_refill->total          = $refill_quantity+$sms_remaining;
                $new_refill->count          = $refill_count; // The total numbers of refills that have been made
                $new_refill->sms_unit_price = $sms_unit_price;

                /*
                 * Calculate price
                 */
                $vat_rate                   = config('rl_sms.vat_rate');
                $vat_dividor                = ($vat_rate / 100)+1;
                $price_excl_vat             = $sms_refill_sum; // Total refill amount excl vat
                $price_incl_vat             = number_format($price_excl_vat * $vat_dividor, 2, '.', '');
                $price_vat_total            = number_format($price_incl_vat-$price_excl_vat, 2, '.', '');

                $new_refill->vat_rate       = $vat_rate;
                $new_refill->price_incl_vat = $price_incl_vat;
                $new_refill->price_excl_vat = $price_excl_vat;
                $new_refill->price_vat      = $price_vat_total;

                $new_refill->save();

                if(!empty($group)) {
                    foreach($group->team->members as $recipient){
                        $recipient->user->notify(new Notifier('sms_refilled', [
                            'id'                => $new_refill->id,
                            'name'              => $recipient->user->firstname,
                            'link'              => route('rl_sms.admin.sms.index'),
                            'refill.quantity'   => $sms_refill_sum,
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

