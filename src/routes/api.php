<?php

use Illuminate\Support\Facades\Route;

use Rocketlabs\Sms\App\Http\Controllers\Api\SmsController;

// Route helper.
$route = function ($accessor, $default = '') {
    return $this->app->config->get('rl_sms.routes.api.'.$accessor, $default);
};

// Middleware helper.
//$middleware = function ($accessor, $default = []) {
//    return $this->app->config->get('rl_sms.middleware.'.$accessor, $default);
//};

//Route::group(['middleware' => $middleware('api')], function () use ($route, $middleware) {
//
//});

Route::get($route('test'), [SmsController::class, 'test']);

Route::get($route('server_status'), [SmsController::class, 'getServerStatus']);