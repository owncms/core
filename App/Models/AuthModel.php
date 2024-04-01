<?php

namespace Modules\Core\App\Models;

use Illuminate\Foundation\Auth\User as BaseAuthUser;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Core\App\Traits\ModelTrait;

class AuthModel extends BaseAuthUser
{
    use SoftDeletes;
    use ModelTrait;

    /**
     * @var array
     */
    public array $translatable = [];
}
