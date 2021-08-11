<?php

use Illuminate\Support\Facades\Route;
use Rocketlabs\Sms\App\Http\Controllers\SmsController;
use Rocketlabs\Sms\App\Http\Controllers\SendersController;



// Route helper.
$route = function ($accessor, $default = '') {
    return $this->app->config->get('rl_sms.routes.'.$accessor, $default);
};

// Middleware helper.
$middleware = function ($accessor, $default = []) {
    return $this->app->config->get('rl_sms.middleware.'.$accessor, $default);
};

Route::group(['middleware' => 'web'], function () use ($route, $middleware) {

    Route::group(['middleware' => $middleware('global')], function () use ($route, $middleware) {

        /*
        * Admin routes
        */
        Route::group(['middleware' => $middleware('admin.global')], function () use ($route, $middleware) {

            /*
             * Sms
             */
            Route::get($route('admin.sms.index'), [SmsController::class, 'index'])
                ->middleware($middleware('admin.sms.index'))
                ->name('rl_sms.admin.sms.index');

            Route::get($route('admin.sms.view'), [SmsController::class, 'view'])
                ->middleware($middleware('admin.sms.view'))
                ->name('rl_sms.admin.sms.view');

            Route::get($route('admin.sms.filter'), [SmsController::class, 'filter'])
                ->middleware($middleware('admin.sms.filter'))
                ->name('rl_sms.admin.sms.filter');

            Route::get($route('admin.sms.clearfilter'), [SmsController::class, 'clear_filter'])
                ->middleware($middleware('admin.sms.clearfilter'))
                ->name('rl_sms.admin.sms.clearfilter');

            Route::get($route('admin.sms.chart'), [SmsController::class, 'chart'])
                ->middleware($middleware('admin.sms.chart'))
                ->name('rl_sms.admin.sms.chart');

            Route::post($route('admin.sms.send'), [SmsController::class, 'send'])
                ->middleware($middleware('admin.sms.send'))
                ->name('rl_sms.admin.sms.send');

            /*
             * Senders
             */
            Route::get($route('admin.senders.index'), [SendersController::class, 'index'])
                ->middleware($middleware('admin.senders.index'))
                ->name('rl_sms.admin.senders.index');

            Route::get($route('admin.senders.edit'), [SendersController::class, 'edit'])
                ->middleware($middleware('admin.senders.edit'))
                ->name('rl_sms.admin.senders.edit');

            Route::get($route('admin.senders.create'), [SendersController::class, 'create'])
                ->middleware($middleware('admin.senders.create'))
                ->name('rl_sms.admin.senders.create');

            Route::get($route('admin.senders.store'), [SendersController::class, 'store'])
                ->middleware($middleware('admin.senders.store'))
                ->name('rl_sms.admin.senders.store');
        });

    });

});
