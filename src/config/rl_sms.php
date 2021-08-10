<?php

return [

    'tables' => [
        'sms'                         => 'sms',
        'refills'                     => 'sms_refills',
    ],

    'models' => [
        'sms'                         => \Rocketlabs\Sms\App\Models\Sms::class,
        'refills'                     => \Rocketlabs\Sms\App\Models\Refills::class,
    ],
    
	'routes' => [
		// Management routes
		'admin' => [
            'sms' => [
                'index'         => '/admin/sms',
                'view'          => '/admin/sms/view/{id}',
                'filter'        => '/admin/sms/filter',
                'clearfilter'   => '/admin/sms/clearfilter',
                'chart'         => '/admin/sms/chart'
            ],
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

    'refill' => [
        'amount'    => 500,
        'threshold' => 100,
    ]

];