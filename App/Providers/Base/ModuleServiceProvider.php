<?php

namespace Modules\Core\App\Providers\Base;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\ServiceProvider;

class ModuleServiceProvider extends ServiceProvider
{
    private $application;

    /**
     * Boot the application events.
     */
    public function boot(): void
    {
        $this->registerCommands();
        $this->registerCommandSchedules();
        $this->registerTranslations();
        $this->registerConfig();
        $this->registerViews();
        $this->loadMigrationsFrom(module_path($this->moduleName, 'Database/migrations'));

        $this->registerHelpers();
        $this->registerObservers();
        $this->application = resolve('application');
    }

    /**
     * Register commands in the format of Command::class
     */
    protected function registerCommands(): void
    {
        // $this->commands([]);
    }

    /**
     * Register command Schedules.
     */
    protected function registerCommandSchedules(): void
    {
        // $this->app->booted(function () {
        //     $schedule = $this->app->make(Schedule::class);
        //     $schedule->command('inspire')->hourly();
        // });
    }

    /**
     * Register translations.
     */
    public function registerTranslations(): void
    {
        $langPath = resource_path('lang/modules/' . $this->moduleNameLower);

        if (is_dir($langPath)) {
            $this->loadTranslationsFrom($langPath, $this->moduleNameLower);
            $this->loadJsonTranslationsFrom($langPath);
        } else {
            $this->loadTranslationsFrom(module_path($this->moduleName, 'lang'), $this->moduleNameLower);
            $this->loadJsonTranslationsFrom(module_path($this->moduleName, 'lang'));
        }
    }

    /**
     * Register config.
     */
    protected function registerConfig(): void
    {
        $this->publishes(
            [
                module_path($this->moduleName, 'config/config.php') => config_path($this->moduleNameLower . '.php')
            ],
            'config'
        );
        $this->mergeConfigFrom(module_path($this->moduleName, 'config/config.php'), $this->moduleNameLower);
    }

    /**
     * Register views.
     */
    public function registerViews(): void
    {
        $viewPath = resource_path('views/modules/' . $this->moduleNameLower);
        $sourcePath = module_path($this->moduleName, 'resources/views');

        $this->publishes([$sourcePath => $viewPath], ['views', $this->moduleNameLower . '-module-views']);

        $this->loadViewsFrom(array_merge($this->getPublishableViewPaths(), [$sourcePath]), $this->moduleNameLower);

        $componentNamespace = str_replace(
            '/',
            '\\',
            Config::get('modules.namespace') . '\\' . $this->moduleName . '\\' . Config::get(
                'modules.paths.generator.component-class.path'
            )
        );
        Blade::componentNamespace($componentNamespace, $this->moduleNameLower);
    }

    /**
     * @return array
     */
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

    /**
     * @return mixed
     */
    public function getApplication(): mixed
    {
        return $this->application;
    }

    /**
     * @return void
     */
    public function registerObservers(): void
    {
        $observers = Config::get($this->moduleNameLower . '.observers');
        if (!$observers) {
            return;
        }
        foreach ($observers as $model => $observerClass) {
            $model::observe($observerClass);
        }
    }

    /**
     * @return void
     */
    public function registerHelpers(): void
    {
        $files_path = module_path($this->moduleNameLower, 'Helpers/*.php');

        foreach (glob($files_path) as $file) {
            require_once $file;
        }
    }

    /**
     * @return string
     */
    public function getModuleNamespace(): string
    {
        return "Modules\/" . $this->moduleName . "\App\Http\Controllers";
    }
}
