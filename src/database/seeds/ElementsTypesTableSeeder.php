<?php namespace Rocketlabs\Forms\Seeds;

use Illuminate\Database\Seeder;

use DB;

class ElementsTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table(config('rl_forms.tables.forms_elements_types'))->truncate();

        $elements = [

            [
                'id' => 1,
                'label' => 'Input',
                'description' => 'Input field',
                'slug' => 'input',
                'enabled' => 1,
                'sort_order' => 0,
                'data_type' => 'string',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 2,
                'label' => 'Dropdown',
                'description' => 'Single choice dropdown',
                'slug' => 'select',
                'enabled' => 1,
                'sort_order' => 2,
                'data_type' => 'string',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 3,
                'label' => 'Dropdown, multiselect',
                'description' => 'Multiple choice dropdown',
                'slug' => 'multiselect',
                'enabled' => 1,
                'sort_order' => 3,
                'data_type' => 'array',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 4,
                'label' => 'Checkbox',
                'description' => 'Multiple choice alternative',
                'slug' => 'checkbox',
                'enabled' => 1,
                'sort_order' => 4,
                'data_type' => 'array',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 5,
                'label' => 'Radio',
                'description' => 'Single choice alternative',
                'slug' => 'radio',
                'enabled' => 1,
                'sort_order' => 5,
                'data_type' => 'string',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],
            [
                'id' => 6,
                'label' => 'Textarea',
                'description' => 'Textarea field',
                'template' => 'textarea',
                'enabled' => 1,
                'sort_order' => 6,
                'data_type' => 'text',
                'created_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s'),
                'updated_at' => \Carbon\Carbon::now()->format('Y-m-d H:i:s')
            ],

        ];

        DB::table(config('rl_forms.tables.forms_elements_types'))->insert($elements);

    }
}
