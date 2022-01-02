<?php

namespace Modules\Core\Providers\Base;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected $routeType;
    protected $prefixAdmin;

    /**
     * Called before routes are registered.
     *
     * Register any model bindings or pattern based filters.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();
        $config = config('core');
        $this->routeType = $config['route_type'];
        $this->prefixAdmin = $config['prefix_admin'];
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
//        if ($this->routeType == 'backend') {
            $this->mapWebRoutes();
//        } else {
            $this->mapFrontRoutes();
//        }
        $this->mapApiRoutes();
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
        Route::prefix($this->prefixAdmin)
            ->as('admin.')
            ->middleware('web')
            ->namespace($this->moduleNamespace)
            ->group(module_path($this->moduleName, '/Routes/web.php'));
    }

    /**
     * Define the "front" routes for the application.
     *
     * @return void
     */
    protected function mapFrontRoutes()
    {
        $path = module_path($this->moduleName, '/Routes/front.php');
        if (file_exists($path)) {
            Route::as('Front::' . $this->moduleNameLower . '.')
                ->middleware('front')
                ->namespace($this->moduleNamespace)
                ->group($path);
        }
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
        $path = module_path($this->moduleName, 'Routes/api.php');
        if (file_exists($path)) {
            Route::prefix('api')
                ->middleware('api')
                ->namespace($this->moduleNamespace)
                ->group($path);
        }
    }
}
