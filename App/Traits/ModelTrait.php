<?php

namespace Modules\Core\App\Traits;

trait ModelTrait
{
    public function attributesToUnset()
    {
        foreach ($this->getAttributes() as $key => $value) {
            if (in_array($key, $this->attributesUnset)) {
                unset($this->{$key});
            }
        }
    }
}
