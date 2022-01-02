<?php

namespace Modules\Core\src\Modules;

use Modules\Core\src\IBaseModule;
use Illuminate\Support\Facades\Artisan;

abstract class BaseModule implements IBaseModule
{
    private $module;
    private $overrideMigration;

    public function __construct($module, $overrideMigration = false)
    {
        $this->module = $module;
        $this->overrideMigration = $overrideMigration;
        $this->baseInstall();
    }

    public function baseInstall()
    {
        if (!$this->overrideMigration) {
//            Artisan::call('module:migrate ' . $this->module['module']);
        }
    }
}
