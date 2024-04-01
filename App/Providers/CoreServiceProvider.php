<?php

namespace Modules\Core\App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Modules\Core\App\Console\InstallCommand;
use Modules\Core\App\Foundation\Application;
use Config;
use Modules\Core\App\src\Installation\Installation;
use Modules\Core\App\src\Software\ISoftware;
use Modules\Core\App\src\Software\Software;
use Modules\Core\App\Http\Controllers\Backend\InstallationController;
use Modules\Core\App\Providers\Base\ModuleServiceProvider;
use Illuminate\Contracts\Http\Kernel;
use Illuminate\Routing\Router;

class CoreServiceProvider extends ModuleServiceProvider
{
    /**
     * @var string
     */
    protected string $moduleName = 'Core';

    /**
     * @var string
     */
    protected string $moduleNameLower = 'core';

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
//        $router = $this->app['router'];
//        if (!(new Installation)->checkInstallationStatus() && app('application')->isFrontend()) {
//            $router->pushMiddlewareToGroup('front', \Modules\Core\Http\Middleware\Installation::class);
//            $ipInstallation = getenv('IP_Installation');
//            //todo
//            if (!$ipInstallation) {
////                throw new \Exception('Make sure you have set the .env key: IP_Installation key');
//            }
//            if ($ipInstallation != request()->ip()) {
////                return abort(403);
//            }
////            $this->loadInstallationRoutes();
//        }
        parent::boot();

        $this->commands(
            [
                InstallCommand::class,
            ]
        );
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
//        $loader = AliasLoader::getInstance();
//        $this->app->make(Application::class);
//        $this->app->bind('application', Application::class);
//        $loader->alias('Application', \Modules\Core\App\Facades\Application::class);
//
//        $this->app->bind(ISoftware::class, Software::class);
//        $this->app->singleton('CoreSoftware', function () {
//            return new Software;
//        });
//        $this->app->singleton('Modules', function () {
//            return resolve('application')->getModules();
//        });
//        $this->app->singleton('ActiveModules', function () {
//            return resolve('application')->getActiveModules();
//        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig(): void
    {
        parent::registerConfig();
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'config/installation.php'),
            $this->moduleNameLower
        );
    }

    /**
     * @return void
     */
    private function loadInstallationRoutes()
    {
        Route::get('installation/{any?}', function () {
            return view('core::installation.app');
        })->where('any', '.*');
    }
}
