<?php

namespace Modules\Core\Foundation;

use Illuminate\Foundation\Application;

class BaseApplication extends Application
{
    /**
     * Create a new Illuminate application instance.
     *
     * @param string|null $basePath
     * @return void
     */
    public function __construct($basePath = null)
    {
        parent::__construct($basePath);
    }

    /**
     * Get the path to the application configuration files.
     *
     * @param string $path Optionally, a path to append to the config path
     * @return string
     */
    public function configPath($path = '')
    {
        return $this->basePath . DIRECTORY_SEPARATOR . 'vendor/owncms/core/Config';
    }
}
