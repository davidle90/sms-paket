<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;

use DB;
use Rocketlabs\Sms\Seeds\AclAbilitiesTableSeeder;

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


    }
}