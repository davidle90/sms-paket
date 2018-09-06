<?php namespace Rocketlabs\Forms;

use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;

class FormsServiceProvider extends ServiceProvider
{
	/**
	 * Bootstrap the application services.
	 *
	 * @return void
	 */
	public function boot(\Illuminate\Routing\Router $router)
	{
		/*
		 * Register RouteServiceProvider
		 * */
		$this->app->register(\Rocketlabs\Forms\App\Providers\RouteServiceProvider::class);

		/*
		 * Register view with namespace
		 * */
		$this->loadViewsFrom(__DIR__.'/resources/views', 'rl_forms');

		/*
		 * Register language files
		 * */
		$this->loadTranslationsFrom(__DIR__.'/resources/lang', 'rl_forms');

		/*
		 * Register migrations path
		 * */
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');

		/*
		 * Register publish command to publish views folder to vendor
		 * */
		$this->publishes([
			__DIR__.'/resources/views' => resource_path('views/vendor/forms'),
		], 'views');

		$this->publishes([
			__DIR__.'/resources/assets' => resource_path('assets'),
		], 'assets');

		$this->publishes([
			__DIR__ .'/config/rl_forms.php'    => config_path('rl_forms.php'),
		], 'config');

		/*
		 * Register middlewares
		 * */
		$this->registerMiddlewares($router);
	}

	/**
	 * Register the application services.
	 *
	 * @return void
	 */
	public function register()
	{
		$this->mergeConfigFrom(
			__DIR__.'/config/rl_forms.php', 'rl_forms'
		);
	}

	public function registerMiddlewares($router)
	{

	}

}
