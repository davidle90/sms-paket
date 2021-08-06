<?php

return [

    'tables' => [
        'sms'                         => 'sms',
    ],

    'models' => [
        'sms'                         => \Rocketlabs\Sms\App\Models\Sms::class,
    ],
    
	'routes' => [
		// Management routes
		'admin' => [
            'sms' => [
                'index'     => '/admin/sms',
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
                'sms'     => ['can:sms_view'],
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