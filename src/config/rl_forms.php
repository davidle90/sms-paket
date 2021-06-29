<?php

return [

    'tables' => [
        'forms'                         => 'forms',
        'forms_sourceable'              => 'forms_sourceable',
        'forms_sections'                => 'forms_sections',
        'forms_sections_elements'       => 'forms_sections_elements',
        'forms_elements'                => 'forms_elements',
        'forms_elements_types'          => 'forms_elements_types',
        'forms_elements_options'        => 'forms_elements_options',
        'forms_responses'               => 'forms_responses',
        'forms_responses_data'          => 'forms_responses_data',
        'forms_responses_data_single'   => 'forms_responses_data_single',
        'forms_responses_data_text'     => 'forms_responses_data_text',
        'forms_responses_data_multiple' => 'forms_responses_data_multiple',
    ],

    'models' => [
        'forms'                         => \Rocketlabs\Forms\App\Models\Forms::class,
        'forms_sourceable'              => \Rocketlabs\Forms\App\Models\Forms\Sourceable::class,
        'forms_sections'                => \Rocketlabs\Forms\App\Models\Forms\Sections::class,
        'forms_sections_elements'       => \Rocketlabs\Forms\App\Models\Forms\Sections\Elements::class,
        'forms_elements'                => \Rocketlabs\Forms\App\Models\Forms\Elements::class,
        'forms_elements_types'          => \Rocketlabs\Forms\App\Models\Forms\Elements\Types::class,
        'forms_elements_options'        => \Rocketlabs\Forms\App\Models\Forms\Elements\Options::class,
        'forms_responses'               => \Rocketlabs\Forms\App\Models\Responses::class,
        'forms_responses_data'          => \Rocketlabs\Forms\App\Models\Responses\Data::class,
        'forms_responses_data_single'   => \Rocketlabs\Forms\App\Models\Responses\Data\Single::class,
        'forms_responses_data_text'     => \Rocketlabs\Forms\App\Models\Responses\Data\Text::class,
        'forms_responses_data_multiple' => \Rocketlabs\Forms\App\Models\Responses\Data\Multiple::class,
    ],

	'routes' => [
		// Management routes
		'admin' => [
            'forms' => [
                'index'     => '/admin/forms',
                'create'    => '/admin/forms/create',
                'edit'      => '/admin/forms/edit/{id}',
                'view'      => '/admin/forms/view/{id}',
                'store'     => '/admin/forms/store',
                'drop'      => '/admin/forms/drop',
                'element'   => [
                    'modal' => '/admin/forms/element/modal'
                ],
                'templates' => [
                    'element'   => '/admin/forms/templates/element',
                    'card'      => '/admin/forms/templates/card'
                ],
                'modals' => [
                    'section' => '/admin/forms/modals/section'
                ]
            ]
        ]
	],

    'yields' => [
        'styles'		=> 'styles',
        'scripts'	    => 'scripts',
        'breadcrumbs'   => 'breadcrumbs',
        'header'        => 'header',
        'nav'           => 'nav',
        'content'	    => 'content',
        'modals'		=> 'modals',
        'sidebar'		=> 'sidebar',
    ],

    'middleware' => [
        'global'	=> [],
        'admin' => [
            'global'    => ['auth', 'acl', 'user','can:admin_access'],
            'tables'    => [
                'index'     => ['can:tables_view'],
                'create'    => ['can:tables_create'],
                'view'      => ['can:tables_view'],
                'edit'      => ['can:tables_edit'],
                'store'     => ['can:tables_edit'],
                'drop'      => ['can:tables_delete'],
            ]
        ],
        'app' => [
            'global'    => ['auth', 'acl', 'user'],
        ],
        'public' => [
            'global'    => [],
        ],
    ],

];