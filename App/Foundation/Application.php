<?php

namespace Modules\Core\App\Foundation;

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
    private $prefixAdmin;
    const VERSION = 0.2;
    const AUTHOR = 'Damian Białkowski';
    const APP_NAME = 'OwnCMS';

    /**
     * Application constructor.
     * @param BaseApplication $app
     */
    public function __construct(
        BaseApplication $app
    ) {
        $this->app = $app;
        $this->request = $app->request;
        $this->prefixAdmin = env('ADMIN_ROUTE', 'admin');
        $this->initBase();
    }

    /**
     * @return void
     */
    public function initBase(): void
    {
        Config::set('core.route_type', $this->getRouteType());
        Config::set('core.prefix_admin', $this->getPrefixAdmin());
        Config::set('system.is_frontend', $this->isFrontend());
    }

    /**
     * @return string
     */
    public function getRouteType(): string
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
        return Str::contains($this->request->url(), $this->prefixAdmin);
    }

    /**
     * @return bool
     */
    public function isFrontend(): bool
    {
        return !$this->isBackend() && !app()->runningInConsole();
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
     * @return bool
     */
    public function isAppInstalled(): bool
    {
        return file_exists(storage_path('framework/owncms_installation.txt'));
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

    /**
     * Get App name
     * @return string
     */
    public static function getAppName(): string
    {
        return self::APP_NAME;
    }

    /**
     * @return string
     */
    public function getPrefixAdmin(): string
    {
        return $this->prefixAdmin;
    }
}
