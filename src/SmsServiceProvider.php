<?php namespace Rocketlabs\Sms;


use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\View\View;

class SmsServiceProvider extends ServiceProvider
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
        $this->loadViewsFrom(__DIR__.'/resources/views', 'rl_sms');
        $this->loadTranslationsFrom(__DIR__.'/resources/lang', 'rl_sms');
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        // Register publish command to publish views folder to vendor
        $this->publishes([__DIR__.'/resources/views' => resource_path('views/vendor/rl_sms')], 'views');
        $this->publishes([__DIR__.'/resources/assets' => resource_path('assets')], 'assets');
        $this->publishes([__DIR__.'/resources/lang' => resource_path('lang/vendor/rl_sms')], 'lang');
        $this->publishes([__DIR__.'/config/rl_urls.php' => config_path('rl_sms.php')], 'config');

        // Register middlewares
        $this->registerMiddlewares($router);

        // Register command
        if($this->app->runningInConsole()){
            $this->commands([
                \Rocketlabs\Sms\App\Console\Commands\RefillSms::class,
                \Rocketlabs\Sms\App\Console\Commands\SendQueuedSms::class,
            ]);
        }

        // Register scheduler after the app has been booted
        $this->app->booted(function () {
            // Schedule class
            $schedule = $this->app->make(Schedule::class);

            // Run schedule for refill
            $schedule->command('sms:refill')
                ->dailyAt(config('rl_sms.schedule.refill'));

            // Run schedule for send queued sms
            $schedule->command('sms:send_queued_sms')
                ->everyMinute();
        });

    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/rl_sms.php', 'rl_sms'
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
        $this->loadRoutesFrom(__DIR__.'/routes/sms.php');
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
    }

}
