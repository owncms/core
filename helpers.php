<?php

/**
 * Get module list
 */
if (!function_exists('modules')) {
    function modules()
    {
        return Application::getModules();
    }
}

/**
 * URL to specific module assets
 */
if (!function_exists('module_asset')) {
    function module_asset($path): string
    {
        $prefix = module_prefix();
        return asset("modules/$prefix/$path");
    }
}

if (!function_exists('module_prefix')) {
    function module_prefix(): string
    {
        if (!request()->route()) {
            return '';
        }
        $prefix = request()->route()->getPrefix();
        if (str_contains($prefix, '/')) {
            $prefix = (explode('/', $prefix))[1];
        }
        return $prefix;
    }
}

if (!function_exists('module_lang')) {
    function module_lang($trans = '', $moduleName = false): mixed
    {
        if ($trans == '') {
            return '';
        }
        $trans = str_replace("'", '', $trans);
        $controller = request()->route()->controller;
        if (!strpos($trans, '::')) {
            if (!$moduleName) {
                $modulePrefix = module_prefix();
                $transNew = $modulePrefix . '::';
                $baseRoute = $controller->getBaseRoute();
                if ($baseRoute) {
                    $transNew .= $baseRoute . '.';
                }
            } else {
                $transNew = $moduleName . '::';
            }
            $transNew .= $trans;
            $trans = $transNew;
        }
        return app('translator')->get($trans);
    }
}

/**
 * Check whether the specific module is enabled
 */
if (!function_exists('is_module_enabled')) {
    function is_module_enabled($module): bool
    {
        return array_key_exists($module, app('modules')->all());
    }
}

if (!function_exists('getGuardName')) {
    function getGuardName(): mixed
    {
        foreach (array_keys(config('auth.guards')) as $guard) {
            if (auth()->guard($guard)->check()) {
                return $guard;
            }
        }
        return null;
    }
}

if (!function_exists('app_memory_usage')) {
    function app_memory_usage($uppercase = false): float
    {
        $size = memory_get_usage(true);
        $units = ['kb', 'mb', 'gb', 'tb', 'pb'];
        if ($uppercase) {
            array_walk($units, function (&$unit) {
                $unit = strtoupper($unit);
            });
        }
        return round($size / pow(1024, ($i = floor(log($size, 1024)))), 1) . $units[$i];
    }
}

if (!function_exists('cms_version')) {
    function cms_version(): string
    {
        return \Application::getVersion();
    }
}

if (!function_exists('base_admin_url')) {
    function base_admin_url(): string
    {
        return url(env('ADMIN_ROUTE', 'admin'));
    }
}

if (!function_exists('cms_author')) {
    function cms_author()
    {
        return \Application::getAuthor();
    }
}

if (!function_exists('build_crud_route')) {
    function build_crud_route($method, $id = null): string
    {
        $controller = request()->route()->controller;
        $baseRoute = $controller->routeWithModulePrefix;
        return route($baseRoute . '.' . $method, $id);
    }
}

if (!function_exists('pascal_to_snake')) {
    function pascal_to_snake($value): string
    {
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $value));
    }
}

if (!function_exists('get_current_method')) {
    function get_current_method(): string
    {
        $method = explode('@', Route::currentRouteAction());
        return end($method);
    }
}
