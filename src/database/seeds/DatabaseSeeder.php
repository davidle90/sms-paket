<?php namespace Rocketlabs\Forms\Seeds;

use Illuminate\Database\Seeder;

use DB;
use Rocketlabs\Forms\Seeds\AclAbilitiesTableSeeder;
use Rocketlabs\Forms\Seeds\ElementsTypesTableSeeder;

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

        /*
         * Element types
         */
        $this->call(ElementsTypesTableSeeder::class);

    }
}