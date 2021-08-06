<?php

namespace Modules\Core\src\Installation;

use File;

class Installation
{
    //1. Composer update
    //2. plik .env kopia - ustawianie domyslnych danych

    public function checkInstallationStatus(): bool
    {
        return file_exists(storage_path('framework/dcms_installation.txt'));
    }

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

    public function checkRequirements(): array
    {
        $requirementsConfig = [
            'php' => config('core.requirements.php'),
            'apache' => config('core.requirements.apache'),
        ];
        $software = resolve('CoreSoftware');
        $requirements = [];
        foreach ($requirementsConfig as $type => $extensions) {
            if (!is_array($extensions)) {
                $extensions = [$extensions];
            }
            foreach ($extensions as $extension) {
                $requirements[$type][$extension] = $type == 'php' ?
                    $software->isPhpExtensionLoaded($extension) :
                    $software->isApacheModuleEnabled($extension);
            }
        }
        return $requirements;
    }

    public function checkPermissions(): array
    {
        $permissionsConfig = config('core.requirements.permissions');
        $requirements = [];
        foreach ($permissionsConfig as $key => $path) {
            $requirements[$key] = is_writable($path);
        }
        return $requirements;
    }
}
