<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Str;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $namespace = 'App\Http\Controllers';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapApiRoutes();

        $this->mapWebRoutes();
        $allRoutesFilePath = $this->loadRoutesFile(base_path('routes'));
        $this->makeFileRoutes($allRoutesFilePath);

        //
    }

    protected function makeFileRoutes($allRoutesFilePath)
    {
        foreach ($allRoutesFilePath as $routesFilePath) {
            if (Str::endsWith($routesFilePath, '.web.php')) { //匹配需要分配web中间的文件
                Route::middleware('web')
                    ->namespace($this->namespace)
                    ->group($routesFilePath);
            }
            if (Str::endsWith($routesFilePath, '.api.php')) { //匹配需要分配api中间的文件
                Route::prefix('api')
                    ->namespace($this->namespace)
                    ->group($routesFilePath);
            }
            if (Str::endsWith($routesFilePath, '.app.php')) { //匹配需要分配api中间的文件
                Route::prefix('app')
                    ->namespace($this->namespace)
                    ->group($routesFilePath);
            }
        }
    }


    /**
     * Define the "web" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function mapWebRoutes()
    {
        Route::middleware('web')
             ->namespace($this->namespace)
             ->group(base_path('routes/web.php'));
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function mapApiRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->namespace)
             ->group(base_path('routes/api.php'));
    }

    /**
     * 载入路由文件
     *
     * @param [type] $path
     * @return void
     */
    protected function loadRoutesFile($path)
    {
        $allRoutesFilePath = array();
        foreach (glob($path) as $file) {
            if (is_dir($file)) {
                $allRoutesFilePath = array_merge($allRoutesFilePath, $this->loadRoutesFile($file . '/*'));
            } else {
                $allRoutesFilePath[] = $file;
            }
        }
        return $allRoutesFilePath;
    }
}
