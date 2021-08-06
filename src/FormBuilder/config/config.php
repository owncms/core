<?php

return [
    'defaults'      => [
        'wrapper_class'       => 'form-group',
        'wrapper_error_class' => 'has-error',
        'label_class'         => 'control-label',
        'field_class'         => 'form-control',
        'field_error_class'   => '',
        'help_block_class'    => 'help-block',
        'error_class'         => 'text-danger',
        'required_class'      => 'required'

        // Override a class from a field.
        //'text'                => [
        //    'wrapper_class'   => 'form-field-text',
        //    'label_class'     => 'form-field-text-label',
        //    'field_class'     => 'form-field-text-field',
        //]
        //'radio'               => [
        //    'choice_options'  => [
        //        'wrapper'     => ['class' => 'form-radio'],
        //        'label'       => ['class' => 'form-radio-label'],
        //        'field'       => ['class' => 'form-radio-field'],
        //],
    ],
    // Templates
    'form'          => 'form-builder::form',
    'text'          => 'form-builder::text',
    'textarea'      => 'form-builder::textarea',
    'button'        => 'form-builder::button',
    'buttongroup'   => 'form-builder::buttongroup',
    'radio'         => 'form-builder::radio',
    'checkbox'      => 'form-builder::checkbox',
    'select'        => 'form-builder::select',
    'choice'        => 'form-builder::choice',
    'repeated'      => 'form-builder::repeated',
    'child_form'    => 'form-builder::child_form',
    'collection'    => 'form-builder::collection',
    'static'        => 'form-builder::static',


    'editor'        => 'form-builder::editor',

    // Remove the laravel-form-builder:: prefix above when using template_prefix
    'template_prefix'   => '',

    'default_namespace' => '',

    'custom_fields' => [
//        'datetime' => App\Forms\Fields\Datetime::class
    ]
];
