<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;
use DB;

class NotificationsTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        // Create new user, database notification
        $container = [
            'label'         => 'SMS påfyllt',
            'slug'          => 'sms_refilled',
            'description'   => 'Denna notifikation skickas vid påfyllning av SMS-pott.',
            'created_at'    => now()->format('Y-m-d H:i:s'),
            'updated_at'    => now()->format('Y-m-d H:i:s'),
        ];

        $container_id = DB::table(config('rl_notifications.tables.containers'))->insertGetId($container);

        $channel = [
            'container_id'  => $container_id,
            'channel_id'    => 1,
            'data'          => '{"id":"database","type":"info","icon":"fal fa-comments-alt","label":"SMS-påfyllning: +%refill.quantity%kr","message":"Din SMS-pot har fyllts p\u00e5 med %refill.quantity%kr."}',
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        DB::table(config('rl_notifications.tables.containers_channels'))->insert($channel);

        $channel = [
            'container_id'  => $container_id,
            'channel_id'    => 3,
            'data'          => '{"id":"mail","template":"6","subject":"SMS p\u00e5fyllt","message":"SMS-potten har fyllts p\u00e5 med %refill.quantity%st SMS.","footer":"Developed by&nbsp;<a href=\"http:\/\/rocketlabs.se\/\" target=\"_blank\" rel=\"nofollow\">RocketLabs<\/a>, the Swedish R&D web agency."}',
            'created_at'    => now(),
            'updated_at'    => now(),
        ];

        DB::table(config('rl_notifications.tables.containers_channels'))->insert($channel);

    }
}