<?php

return [

    'tables' => [
        'sms'               => 'sms',
        'refills'           => 'sms_refills',
        'smsables'          => 'sms_smsables',
        'senders'           => 'sms_senders',
        'messages'          => 'sms_messages',
        'nexmo_responses'   => 'sms_nexmo',
        'nexmo_receipts'    => 'sms_nexmo_receipts'
    ],

    'models' => [
        'sms'               => \Rocketlabs\Sms\App\Models\Sms::class,
        'refills'           => \Rocketlabs\Sms\App\Models\Refills::class,
        'smsables'          => \Rocketlabs\Sms\App\Models\Smsables::class,
        'senders'           => \Rocketlabs\Sms\App\Models\Senders::class,
        'messages'          => \Rocketlabs\Sms\App\Models\Messages::class,
        'nexmo_responses'   => \Rocketlabs\Sms\App\Models\NexmoResponses::class,
        'nexmo_receipts'    => \Rocketlabs\Sms\App\Models\NexmoReceipts::class
    ],
    
	'routes' => [
		// Management routes
        'webhooks' => [
            'receipts' => '/rl_sms/webhooks/receipts'
        ],

		'admin' => [
            'sms'       => [
                'index'         => '/admin/sms',
                'view'          => '/admin/sms/view/{id}',
                'filter'        => '/admin/sms/filter',
                'clearfilter'   => '/admin/sms/clearfilter',
                'chart'         => '/admin/sms/chart'
            ],
            'senders'   => [
                'index'     => '/admin/senders',
                'edit'      => '/admin/senders/edit/{id}',
                'create'    => '/admin/senders/create',
                'store'     => '/admin/senders/store',
                'drop'      => '/admin/senders/drop'
            ],
            'receivers' => [
                'get'       => '/admin/receivers/get',
                'move_all'  => '/admin/receivers/move_all',
                'update'    => '/admin/receivers/update',
            ],
            'refills'   => [
                'index'         => '/admin/sms/refills',
                'filter'        => '/admin/sms/refills/filter',
                'clearfilter'   => '/admin/sms/refills/clearfilter',
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
            ],
            'sms' => [
                'index'         => ['can:sms_view'],
                'view'          => ['can:sms_view'],
                'filter'        => ['can:sms_view'],
                'clearfilter'   => ['can:sms_view'],
                'chart'         => ['can:sms_view'],
                'send'          => ['can:sms_view'],
            ],
            'senders' => [
                'index'         => ['can:sms_view'],
                'edit'          => ['can:sms_edit'],
                'create'        => ['can:sms_create'],
                'store'         => ['can:sms_edit'],
                'drop'          => ['can:sms_delete'],
            ],
            'receivers' => [
                'get'           => ['can:sms_view'],
                'move_all'      => ['can:sms_view'],
                'update'        => ['can:sms_view'],
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
        'auto'      => false,
        'amount'    => 500, // The total sum (kr) that will be refilled
        'threshold' => 100, // When we fall below this amount (kr) we do a refill
    ],

    'price'     => 1,    // The price for sending a single sms
    'vat_rate'  => 25,      // Vat rate in percent (%) for sms

    'schedule' => [
        'refill' => '02:00'
    ],

    'email' => [
        'webmaster' => 'webmaster@rocketlabs.se'
    ],

    'channels' => [
        'sms' => true
    ]

];
