<?php

namespace Modules\Core\src\FormBuilder\Fields;

class TextareaType extends FormField
{

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'textarea';
    }
}
