<?php namespace Rocketlabs\Forms\Seeds;

use Illuminate\Database\Seeder;

use DB;

class FormElementsTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{

		DB::table('list_forms_elements')->delete();

		$elements = [

            [
                'id'			=> 1,
                'label'			=> 'Checkbox / Radio',
                'description'	=> 'Multiple choice alternative',
                'template'		=> 'multiple',
                'enabled'		=> 1,
                'sort_order'	=> 3,
                'created_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'			=> 2,
                'label'			=> 'Dropdown',
                'description'	=> 'Select field',
                'template'		=> 'dropdown',
                'enabled'		=> 1,
                'sort_order'	=> 2,
                'created_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'			=> 3,
                'label'			=> 'Text',
                'description'	=> 'Text field',
                'template'		=> 'text',
                'enabled'		=> 1,
                'sort_order'	=> 0,
                'created_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id'			=> 4,
                'label'			=> 'Textarea',
                'description'	=> 'Textarea field',
                'template'		=> 'textarea',
                'enabled'		=> 1,
                'sort_order'	=> 1,
                'created_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at'	=> \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],

		];

		DB::table('list_forms_elements')->insert($elements);

	}
}
