<?php

namespace Modules\Core\App\src\Installation;

use File;
use Illuminate\Support\Facades\Config;

class Installation
{
    /**
     * @var string
     */
    protected string $file = 'owncms_installation.txt';
    //1. Composer update
    //2. plik .env kopia - ustawianie domyslnych danych

    /**
     * @return bool
     */
    public function checkInstallationStatus(): bool
    {
        return file_exists(storage_path('framework/' . $this->file));
    }

    /**
     * @return void
     */
    public function createDefaultEnvFile()
    {
        if (!file_exists(base_path('.env'))) {
            if (file_exists('.env.example')) {
                File::move(base_path('.env.example'), base_path('.env'));
            } else {
                $this->generateDefaultEnvFile();
            }
        }
    }

    public function generateDefaultEnvFile()
    {
        //todo
    }

    /**
     * @return array
     */
    public function checkRequirements(): array
    {
        $requirementsConfig = [
            'php' => Config::get('core.requirements.php'),
            'apache' => Config::get('core.requirements.apache'),
        ];
        $software = resolve('CoreSoftware');
        $requirements = [];
        foreach ($requirementsConfig as $type => $extensions) {
            if (!is_array($extensions)) {
                $extensions = [$extensions];
            }
            foreach ($extensions as $extension) {
                switch ($type) {
                    case 'php':
                        $result = $software->isPhpExtensionLoaded($extension);
                        break;
                    case 'apache':
                        $result = $software->isApacheModuleEnabled($extension);
                        break;
                }
                $requirements[$type][$extension] = $result;
            }
        }
        return $requirements;
    }

    /**
     * @return array
     */
    public function checkPermissions(): array
    {
        $permissionsConfig = Config::get('installation.requirements.permissions');
        $requirements = [];
        foreach ($permissionsConfig as $key => $path) {
            $requirements[$key] = is_writable($path);
        }
        return $requirements;
    }
}
