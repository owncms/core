<?php

namespace Modules\Core\App\Models;

use Illuminate\Database\Eloquent\Model;

class CoreModel extends Model
{
    /**
     * @param $attribute
     * @return bool
     */
    public function hasAttribute($attribute): bool
    {
        return array_key_exists($attribute, $this->attributes);
    }
}
