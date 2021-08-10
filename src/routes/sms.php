<?php

use Illuminate\Support\Facades\Route;
use Rocketlabs\Sms\App\Http\Controllers\SmsController;



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
        });

    });

});
