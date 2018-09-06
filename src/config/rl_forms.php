<?php

return [

    'tables' => [
        'forms'                         => 'forms_forms',
        'elements'                      => 'forms_elemens',
        'forms_elements'                => 'forms_forms_elemens',
        'forms_elements_options_'       => 'forms_forms_elemens_options',
        'forms_response'                => 'forms_forms_response',
        'forms_response_data'           => 'forms_forms_response_data',
        'forms_response_data_options'   => 'forms_forms_response_data_options',
    ],

	'routes' => [
		// Management routes
		'admin' => [
		    'pages'     => [
                'forms' => [
                    'index'     => '/admin/forms',
                    'create'    => '/admin/forms/create',
                    'edit'      => [
                        'index' => '/admin/forms/edit/{id}',
                    ],
                    'view' => '/admin/forms/view/{id}',
                    'store' => '/admin/forms/store',
                    'destroy' => '/admin/forms/destroy',
                    'template' => '/admin/forms/template/{template}'
                ]
            ]
        ]
	],

	'yields' => [
		'head'		=> 'styles',
		'footer'	=> 'scripts',
		'content'	=> 'content',
		'modal'		=> 'modal',
		'h1'		=> 'h1',
	],

	'master_file_extend' => 'rl_pagebuilder::layouts.master',

	'middleware' => [
		'global' 			=> [],
		'admin' => [
			'pages'	=> [
			    'forms' => [

                ]
            ]
		]
	],

];