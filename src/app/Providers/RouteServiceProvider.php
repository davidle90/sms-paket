<?php namespace Rocketlabs\Forms\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class RouteServiceProvider extends ServiceProvider
{

    protected $namespace = 'Rocketlabs\Forms\App\Http\Controllers';

    public function boot()
    {
        parent::boot();
    }

    public function map()
    {
        $this->mapWebRoutes();
	}

    protected function mapWebRoutes()
    {
		
		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator(
				__DIR__.'/../../routes'
			), RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($iterator as $file) {
			if ($file->isFile()) {

                Route::group([
                    'middleware' => 'web',
                    'namespace' => $this->namespace,
                ], function ($router) use ($file) {
                    require $file->getPathname();
                });

			}
		}
        
    }

}
