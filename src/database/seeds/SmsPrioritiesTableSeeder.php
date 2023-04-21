<?php namespace Rocketlabs\Sms\Seeds;

use Illuminate\Database\Seeder;

use DB;

class SmsPrioritiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $priorities = [
            [
                'priority'      => 10,
                'translations'  => [
                    'sv' => [
                        'label' => 'Kritisk'
                    ],
                    'en' => [
                        'label' => 'Critical'
                    ]
                ],
                'slug'          => 'critical'
            ],
            [
                'priority'      => 20,
                'translations'  => [
                    'sv' => [
                        'label' => 'Hög'
                    ],
                    'en' => [
                        'label' => 'High'
                    ]
                ],
                'slug'          => 'high'
            ],
            [
                'priority'      => 30,
                'translations'  => [
                    'sv' => [
                        'label' => 'Medel'
                    ],
                    'en' => [
                        'label' => 'Medium'
                    ]
                ],
                'slug'          => 'medium',
            ],
            [
                'priority'      => 40,
                'translations'  => [
                    'sv' => [
                        'label' => 'Låg'
                    ],
                    'en' => [
                        'label' => 'Low'
                    ]
                ],
                'slug'          => 'low'
            ]
        ];

        foreach($priorities as $priority){
            $new_priority = config('rl_sms.models.priorities')::firstOrNew(['slug' => $priority['slug']]);
            $new_priority->priority   = $priority['priority'];
            $new_priority->slug       = $priority['slug'];
            $new_priority->save();

            foreach($priority['translations'] as $locale => $translation) {
                if(isset($translation['label']) && !empty($translation['label'])){
                    $new_priority->setTranslation($locale, [
                        'label' => $translation['label']
                    ]);
                }
            }

            $new_priority->save();
        }
    }
}