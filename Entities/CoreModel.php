<?php

namespace Modules\Core\Entities;

use Illuminate\Database\Eloquent\Model;

class CoreModel extends Model
{
    public function hasAttribute($attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }

}
