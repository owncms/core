<?php

namespace Modules\Core\src\Software;

class Software implements ISoftware
{
    /**
     * Get all software info
     * @return array
     */
    public function all(): array
    {
        return [
            'php_version' => $this->getPhpVersion(),
            'required_php_version' => $this->getRequiredPhpVersion(),
            'passed_version' => $this->checkPhpVersion()
        ];
    }

    /**
     * Get PHP version
     * @return string
     */
    public function getPhpVersion(): string
    {
        return phpversion();
    }

    /**
     * Get required PHP Version
     * @return string
     */
    public function getRequiredPhpVersion(): string
    {
        return config('core.phpVersion');
    }

    /**
     * Check PHP Version
     * @return bool
     */
    public function checkPhpVersion(): bool
    {
        $phpVersion = $this->getPhpVersion();
        $requiredPhpVersion = $this->getRequiredPhpVersion();
        return version_compare($phpVersion, $requiredPhpVersion, '>=');
    }

    /**
     * Get loaded extensions for PHP
     * @return array
     */
    public function getPhpExtensions(): array
    {
        return get_loaded_extensions();
    }

    /**
     * Get loaded modules for Apache
     * @return array
     */
    public function getApacheModules(): array
    {
        if (function_exists('apache_get_modules')) {
            return apache_get_modules();
        }
        return [];
    }

    /**
     * Check whether specific extension for PHP is loaded
     * @param $extension
     * @return bool
     */
    public function isPhpExtensionLoaded($extension): bool
    {
        return extension_loaded($extension);
    }

    /**
     * Check whether specific extension for apache is loaded
     * @param $extension
     * @return bool
     */
    public function isApacheModuleEnabled($extension): bool
    {
        return in_array($extension, $this->getApacheModules());
    }
}
