<?php

use Illuminate\Support\Facades\Route;
use Rocketlabs\Forms\App\Http\Controllers\FormsController;



// Route helper.
$route = function ($accessor, $default = '') {
    return $this->app->config->get('rl_forms.routes.'.$accessor, $default);
};

// Middleware helper.
$middleware = function ($accessor, $default = []) {
    return $this->app->config->get('rl_forms.middleware.'.$accessor, $default);
};

Route::group(['middleware' => 'web'], function () use ($route, $middleware) {

    Route::group(['middleware' => $middleware('global')], function () use ($route, $middleware) {

        /*
        * Admin routes
        */
        Route::group(['middleware' => $middleware('admin.global')], function () use ($route, $middleware) {

            /*
             * Forms
             */
            Route::get($route('admin.forms.index'), [FormsController::class, 'index'])
                ->middleware($middleware('admin.forms.index'))
                ->name('rl_forms.admin.forms.index');

            Route::get($route('admin.forms.create'), [FormsController::class, 'create'])
                ->middleware($middleware('admin.forms.create'))
                ->name('rl_forms.admin.forms.create');

            Route::get($route('admin.forms.view'), [FormsController::class, 'view'])
                ->middleware($middleware('admin.forms.view'))
                ->name('rl_forms.admin.forms.view');

            Route::get($route('admin.forms.edit'), [FormsController::class, 'edit'])
                ->middleware($middleware('admin.forms.edit'))
                ->name('rl_forms.admin.forms.edit');

            Route::get($route('admin.forms.templates.element'), [FormsController::class, 'get_element_template'])
                ->middleware($middleware('admin.forms.edit'))
                ->name('rl_forms.admin.forms.templates.element');

            Route::get($route('admin.forms.modals.section'), [FormsController::class, 'get_section_modal_template'])
                ->middleware($middleware('admin.forms.edit'))
                ->name('rl_forms.admin.forms.modals.section');

            Route::post($route('admin.forms.store'), [FormsController::class, 'store'])
                ->middleware($middleware('admin.forms.store'))
                ->name('rl_forms.admin.forms.store');

            Route::post($route('admin.forms.drop'), [FormsController::class, 'drop'])
                ->middleware($middleware('admin.forms.drop'))
                ->name('rl_forms.admin.forms.drop');

        });

    });

});
