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
        });

    });

});
