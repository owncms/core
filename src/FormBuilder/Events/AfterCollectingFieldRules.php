<?php

namespace Modules\Core\src\FormBuilder\Events;

use Modules\Core\src\FormBuilder\Fields\FormField;
use Modules\Core\src\FormBuilder\Form;
use Modules\Core\src\FormBuilder\Rules;

class AfterCollectingFieldRules
{
    /**
     * The field instance.
     *
     * @var FormField
     */
    protected $field;

    /**
     * The field's rules.
     *
     * @var Rules
     */
    protected $rules;

    /**
     * Create a new after field creation instance.
     *
     * @param Form $form
     * @param FormField $field
     * @return void
     */
    public function __construct(FormField $field, Rules $rules) {
        $this->field = $field;
        $this->rules = $rules;
    }

    /**
     * Return the event's field.
     *
     * @return FormField
     */
    public function getField() {
        return $this->field;
    }

    /**
     * Return the event's field's rules.
     *
     * @return Rules
     */
    public function getRules() {
        return $this->rules;
    }
}
