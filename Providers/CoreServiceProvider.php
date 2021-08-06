<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Modules\Core\Console\InstallCommand;
use Modules\Core\Foundation\Application;
use Config;
use Modules\Core\src\Installation\Installation;
use Modules\Core\src\Software\ISoftware;
use Modules\Core\src\Software\Software;
use Modules\Core\Http\Controllers\Backend\InstallationController;

class CoreServiceProvider extends ServiceProvider
{
    /**
     * @var string $moduleName
     */
    protected string $moduleName = 'Core';

    /**
     * @var string $moduleNameLower
     */
    protected string $moduleNameLower = 'core';

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/Migrations'));

        $this->commands([
            InstallCommand::class,
        ]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $loader = AliasLoader::getInstance();
        $this->app->register(RouteServiceProvider::class);
        $router = $this->app['router'];
        $this->app->make(Application::class);
        $this->app->bind('application', Application::class);
        $loader->alias('Application', \Modules\Core\Facades\Application::class);
        if (!(new Installation)->checkInstallationStatus() && app('application')->isFrontend()) {
            $router->pushMiddlewareToGroup('web', \Modules\Core\Http\Middleware\Installation::class);
            $ipInstallation = getenv('IP_Installation');
            //todo
            if (!$ipInstallation) {
//                throw new \Exception('Make sure you have set the .env key: IP_Installation key');
            }
            if ($ipInstallation != request()->ip()) {
//                return abort(403);
            }
            $this->loadInstallationRoutes();
        }

        $this->app->bind(ISoftware::class, Software::class);
        $this->app->singleton('CoreSoftware', function () {
            return new Software;
        });
        $this->app->singleton('Modules', function () {
            return resolve('application')->getModules();
        });
        $this->app->singleton('ActiveModules', function () {
            return resolve('application')->getActiveModules();
        });
    }

    /**
     * Register config.
     *
     * @return void
     */
    protected function registerConfig()
    {
        $this->publishes([
            module_path($this->moduleName, 'Config/config.php') => config_path($this->moduleNameLower . '.php'),
        ], 'config');
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/config.php'), $this->moduleNameLower
        );
        $this->mergeConfigFrom(
            module_path($this->moduleName, 'Config/installation.php'), $this->moduleNameLower
        );
    }

    /**
     * Register views.
     *
     * @return void
     */
    public function registerViews()
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);

        $sourcePath = module_path($this->moduleName, 'Resources/views');

        $this->publishes([
            $sourcePath => $viewPath
        ], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);
    }

    /**
     * Register translations.
     *
     * @return void
     */
    public function registerTranslations()
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'Resources/lang'), $this->moduleNameLower);
        }
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [];
    }

    private function getPublishableViewPaths(): array
    {
        $paths = [];
        foreach (Config::get('view.paths') as $path) {
            if (is_dir($path . '/modules/' . $this->moduleNameLower)) {
                $paths[] = $path . '/modules/' . $this->moduleNameLower;
            }
        }
        return $paths;
    }

    private function loadInstallationRoutes()
    {
        Route::group(['prefix' => 'installation', 'as' => 'installation', 'middleware' => ['web']], function () {
            Route::get('languages', [InstallationController::class, 'getLanguages'])->name('.languages');
            Route::post('post-languages', [InstallationController::class, 'postLanguages'])->name('.post-languages');

            Route::get('start', [InstallationController::class, 'start'])->name('.start');

            Route::get('requirements', [InstallationController::class, 'getRequirements'])->name('.requirements');
            Route::get('settings', [InstallationController::class, 'getSettings'])->name('.settings');
            Route::post('post-settings', [InstallationController::class, 'postSettings'])->name('.post-settings');
        });
    }
}
