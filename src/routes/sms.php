<?php

use Illuminate\Support\Facades\Route;

use Rocketlabs\Sms\App\Http\Controllers\SmsController;
use Rocketlabs\Sms\App\Http\Controllers\SendersController;
use Rocketlabs\Sms\App\Http\Controllers\ReceiversController;
use Rocketlabs\Sms\App\Http\Controllers\RefillsController;



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

        Route::post($route('webhooks.receipts'), [SmsController::class, 'webhook_receipts'])
            ->middleware($middleware('webhooks.receipts'))
            ->name('rl_sms.webhooks.receipts');

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
             * Refills
             */
            Route::get($route('admin.refills.index'), [RefillsController::class, 'index'])
                ->middleware($middleware('admin.refills.index'))
                ->name('rl_sms.admin.refills.index');

            Route::get($route('admin.refills.filter'), [RefillsController::class, 'filter'])
                ->middleware($middleware('admin.refills.filter'))
                ->name('rl_sms.admin.refills.filter');

            Route::get($route('admin.refills.clearfilter'), [RefillsController::class, 'clear_filter'])
                ->middleware($middleware('admin.refills.clearfilter'))
                ->name('rl_sms.admin.refills.clearfilter');

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

            Route::post($route('admin.senders.store'), [SendersController::class, 'store'])
                ->middleware($middleware('admin.senders.store'))
                ->name('rl_sms.admin.senders.store');

            Route::post($route('admin.senders.drop'), [SendersController::class, 'drop'])
                ->middleware($middleware('admin.senders.drop'))
                ->name('rl_sms.admin.senders.drop');

            /*
             * Receivers
             */
            Route::post($route('admin.receivers.get'), [ReceiversController::class, 'get'])
                ->middleware($middleware('admin.receivers.get'))
                ->name('rl_sms.admin.receivers.get');

            Route::post($route('admin.receivers.move_all'), [ReceiversController::class, 'move_all_receivers'])
                ->middleware($middleware('admin.receivers.move_all'))
                ->name('rl_sms.admin.receivers.move_all');

            Route::post($route('admin.receivers.update'), [ReceiversController::class, 'update_receivers'])
                ->middleware($middleware('admin.receivers.update'))
                ->name('rl_sms.admin.receivers.update');

        });

    });

});
