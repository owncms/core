<?php

namespace Modules\Core;

use Illuminate\Support\Facades\Artisan;
use Modules\Core\src\Modules\BaseModule;

class Module extends BaseModule
{
    public function __construct($module)
    {
        parent::__construct($module, true);
    }

    public function install()
    {
        Artisan::call('migrate');
    }
}
