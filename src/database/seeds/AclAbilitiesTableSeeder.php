<?php namespace Rocketlabs\Forms\Seeds;

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
         * Forms
         */
        $ability_notifications_view = Bouncer::ability()->firstOrCreate([
            'name' => 'forms_view',
            'title' => 'View forms',
        ]);

        $ability_notifications_create = Bouncer::ability()->firstOrCreate([
            'name' => 'forms_create',
            'title' => 'Create forms',
        ]);

        $ability_notifications_edit = Bouncer::ability()->firstOrCreate([
            'name' => 'forms_edit',
            'title' => 'Edit forms',
        ]);

        $ability_notifications_delete = Bouncer::ability()->firstOrCreate([
            'name' => 'forms_delete',
            'title' => 'Delete forms',
        ]);

    }
}