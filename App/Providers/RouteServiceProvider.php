<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\Facades\Route;
use Modules\Core\App\Providers\Base\RouteServiceProvider as BaseRouteServiceProvider;

class RouteServiceProvider extends BaseRouteServiceProvider
{
    protected string $moduleName = 'Core';
    protected string $moduleNameLower = 'core';

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        $this->mapWebRoutes();
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
        $path = module_path($this->moduleName, 'routes/web.php');
        if (!file_exists($path)) {
            return;
        }
        Route::middleware('web')
            ->namespace($this->getModuleNamespace())
            ->group($path);
    }
}
