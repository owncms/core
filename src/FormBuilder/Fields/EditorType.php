<?php

namespace Modules\Core\src\FormBuilder\Fields;

class EditorType extends FormField
{

    /**
     * @inheritdoc
     */
    protected function getTemplate()
    {
        return 'editor';
    }
}
