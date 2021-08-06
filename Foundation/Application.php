<?php

namespace Modules\Core\Foundation;

use Illuminate\Foundation\Application as BaseApplication;
use \Config;
use Illuminate\Support\Arr;
use \Request;
use \Str;
use \Auth;

class Application
{
    protected object $app;
    protected $request;
    const VERSION = 0.1;
    const AUTHOR = 'Damian Białkowski';

    /**
     * Application constructor.
     * @param BaseApplication $app
     */
    public function __construct(
        BaseApplication $app
    )
    {
        $this->app = $app;
        $this->request = $app->request;
        $this->initBase();
    }

    public function initBase(): void
    {
        Config::set('core.route_status', $this->getRouteStatus());
    }

    /**
     * @return string
     */
    public function getRouteStatus(): string
    {
        if ($this->isBackend() || app()->runningInConsole()) {
            return 'backend';
        }
        return 'frontend';
    }

    /**
     * @return bool
     */
    public function isBackend(): bool
    {
        return Str::contains($this->request->url(), env('ADMIN_ROUTE', 'admin'));
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return !$this->isBackend();
    }

    /**
     * @return mixed
     */
    public function getModules()
    {
        return app('modules')->all();
    }

    /**
     * @return mixed
     */
    public function getActiveModules()
    {
        return app('modules')->allEnabled();
    }

    /**
     * @param string $order
     * @return array
     */
    public function getSortedModules(string $order = 'asc'): array
    {
        $activeModules = $this->getActiveModules();
        $modules = [];
        if ($order) {
            foreach ($activeModules as $module) {
                $modules[] = [
                    'module' => $module,
                    'order' => $module->getPriority()
                ];
            }
        }

        return Arr::sort($modules, function ($value) {
            return $value['order'];
        });
    }

    /**
     * Get prefix of module
     * @param $class
     * @return string
     */
    public function getModulePrefix($class): string
    {
        $explode = explode('\\', $class);
        return $explode[1];
    }

    /**
     * Get version of App
     * @return string
     */
    public static function getVersion(): string
    {
        return self::VERSION;
    }

    /**
     * Get author of App
     * @return string
     */
    public static function getAuthor(): string
    {
        return self::AUTHOR;
    }
}
