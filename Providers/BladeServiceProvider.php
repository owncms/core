<?php

namespace Modules\Core\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider
{

    /**
     * Boot the application events.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerAppDirectives();
    }

    public function registerAppDirectives()
    {
        Blade::directive('module_lang', function ($expression) {
            return "<?php echo module_lang({$expression}); ?>";
        });

        Blade::directive('route', function ($expression) {
            return "<?php echo route({$expression}); ?>";
        });
        Blade::directive('cms_author', function () {
            return "<?php echo cms_author(); ?>";
        });

        Blade::directive('svg', function ($arguments) {
            eval("\$params = [$arguments];");
            list($path) = $params;
            $path = public_path($path);
            if (!file_exists($path)) {
                return '';
            }
            return file_get_contents($path);
        });
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
}
