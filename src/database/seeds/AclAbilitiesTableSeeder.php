<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;
use DB;

use Silber\Bouncer\BouncerFacade as Bouncer;
use Rocketlabs\Auth\App\Models\AbilitiesModels;

class AclAbilitiesTableSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {

        /*
         * Sms
         */
        $ability_notifications_view = Bouncer::ability()->firstOrCreate([
            'name' => 'sms_view',
            'title' => 'View forms',
        ]);

        $ability_notifications_create = Bouncer::ability()->firstOrCreate([
            'name' => 'sms_create',
            'title' => 'Create forms',
        ]);

        $ability_notifications_edit = Bouncer::ability()->firstOrCreate([
            'name' => 'sms_edit',
            'title' => 'Edit forms',
        ]);

        $ability_notifications_delete = Bouncer::ability()->firstOrCreate([
            'name' => 'sms_delete',
            'title' => 'Delete forms',
        ]);

    }
}