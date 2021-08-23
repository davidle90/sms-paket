<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;

use DB;
use Rocketlabs\Sms\Seeds\AclAbilitiesTableSeeder;
use Rocketlabs\Sms\Seeds\NotificationsSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        /*
         * ACL Abilities
         */
        $this->call(AclAbilitiesTableSeeder::class);
        $this->call(NotificationsSeeder::class);


    }
}