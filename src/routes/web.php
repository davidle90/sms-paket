<?php

// Route helper.
$route = function ($accessor, $default = '') {
	return $this->app->config->get('rl_forms.routes.'.$accessor, $default);
};

// Middleware helper.
$middleware = function ($accessor, $default = []) {
	return $this->app->config->get('rl_forms.middleware.'.$accessor, $default);
};

Route::group(['middleware' => ['auth','acl','user']], function () use ($route, $middleware) {

	Route::group(['is' => 'administrator'], function () use ($route, $middleware) {

	    // Pages
		Route::get($route('rl_forms.routes.admin.forms.index', '/admin/forms'), 'FormsController@index')
			->name('rl_forms.admin.forms.index');
        Route::get($route('rl_pagebuilder.routes.admin.forms.create', '/admin/forms/create'), 'FormsController@create')
            ->name('rl_forms.admin.forms.create');
		Route::get($route('rl_pagebuilder.routes.admin.forms.edit.index', '/admin/forms/edit/{id}'), 'FormsController@edit')
            ->name('rl_forms.admin.forms.edit');
        Route::get($route('rl_pagebuilder.routes.admin.forms.view', '/admin/forms/view/{id}'), 'FormsController@view')
            ->name('rl_forms.admin.forms.view');

	});

});