<?php

namespace Modules\Core;

use Illuminate\Support\Facades\Artisan;

class Module
{
    public function __construct()
    {

    }

    public function install()
    {
        Artisan::call('migrate');
    }
}
