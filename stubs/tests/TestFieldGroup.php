<?php

namespace Foo\Bar\Baz;

use Forme\Framework\Fields\FieldGroupInterface;

final class TestFieldGroup implements FieldGroupInterface
{
    public function add(): void
    {
        acf_add_local_field_group([
            'key'    => 'group_62c3329e808ca',
            'title'  => 'Test Group',
            'fields' => [
                [
                    'key'               => 'field_62c332ac9568a',
                    'label'             => 'Text',
                    'name'              => 'text',
                    'type'              => 'text',
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => [
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ],
                    'default_value' => '',
                    'placeholder'   => '',
                    'prepend'       => '',
                    'append'        => '',
                    'maxlength'     => '',
                ],
                [
                    'key'               => 'field_62c332ba9568b',
                    'label'             => 'Number',
                    'name'              => 'number',
                    'type'              => 'number',
                    'instructions'      => '',
                    'required'          => 0,
                    'conditional_logic' => 0,
                    'wrapper'           => [
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ],
                    'default_value' => '',
                    'placeholder'   => '',
                    'prepend'       => '',
                    'append'        => '',
                    'min'           => '',
                    'max'           => '',
                    'step'          => '',
                ],
                [
                    'key'                   => 'field_62c332d39568c',
                    'label'                 => 'Select',
                    'name'                  => 'select',
                    'type'                  => 'select',
                    'instructions'          => '',
                    'required'              => 1,
                    'conditional_logic'     => 0,
                    'wrapper'               => [
                        'width' => '',
                        'class' => '',
                        'id'    => '',
                    ],
                    'choices' => [
                        'foo: Foo' => 'foo: Foo',
                        'bar: Bar' => 'bar: Bar',
                        'baz: Baz' => 'baz: Baz',
                    ],
                    'default_value' => false,
                    'allow_null'    => 0,
                    'multiple'      => 0,
                    'ui'            => 0,
                    'return_format' => 'value',
                    'ajax'          => 0,
                    'placeholder'   => '',
                ],
            ],
            'location' => [
                [
                    [
                        'param'    => 'post_type',
                        'operator' => '==',
                        'value'    => 'post',
                    ],
                ],
            ],
            'menu_order'            => 0,
            'position'              => 'normal',
            'style'                 => 'default',
            'label_placement'       => 'top',
            'instruction_placement' => 'label',
            'hide_on_screen'        => '',
            'active'                => true,
            'description'           => '',
            'show_in_rest'          => 0,
        ]);
    }
}
