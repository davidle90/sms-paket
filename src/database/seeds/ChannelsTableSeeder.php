<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;

use DB;

class ChannelsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $elements = [
            [
                'id'	=> 4,
                'label' => 'SMS',
                'slug'  => 'sms',
                'is_active' => config('rl_sms.channels.sms'),
            ]
        ];

        DB::table(config('rl_notifications.tables.channels'))->insert($elements);
    }
}