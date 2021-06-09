<?php namespace Rocketlabs\Froms;


use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class FromsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot(\Illuminate\Routing\Router $router)
    {

        // Register RouteServiceProvider
        $this->registerRoutes();

        // Register view with namespace
        $this->loadViewsFrom(__DIR__.'/resources/views', 'rl_forms');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'rl_forms');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Register publish command to publish views folder to vendor
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/rl_forms')], 'views');
        $this->publishes([__DIR__.'/resources/assets' => resource_path('assets')], 'assets');
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/rl_forms')], 'lang');
        $this->publishes([__DIR__.'/config/rl_urls.php' => config_path('rl_forms.php')], 'config');

        // Register middlewares
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

    /**
     * Register the application routes.
     *
     * @return void
     */
    public function registerRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/forms.php');
    }

}
