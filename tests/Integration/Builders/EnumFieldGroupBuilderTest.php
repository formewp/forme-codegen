<?php

use Forme\CodeGen\Builders\EnumFieldGroupBuilder;
use Forme\CodeGen\Builders\FieldEnumBuilder;
use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use Forme\CodeGen\Utils\Resolvers\ClassReflectionResolver;
use Forme\CodeGen\Utils\Resolvers\Resolver;

beforeEach(function () {
    $container       = bootstrap();
    // we need to mock the resolver
    $mock = mock(Resolver::class)
        ->shouldReceive('classFile')
        ->andReturn(
            mock(ClassFileResolver::class)
                ->shouldReceive('getNameSpace')
                ->andReturn('Foo\\Bar')
                ->getMock()
        )->shouldReceive('classReflection')
        ->andReturn(
            mock(ClassReflectionResolver::class)
                ->shouldReceive('getUseStatements')
                ->andReturn(
                    [
                        'FieldGroupInterface' => "Forme\Framework\Fields\FieldGroupInterface",
                    ]
                )
                ->getMock()
        );

    $arguments       = [
        'name'   => 'FooBar',
        'class'  => 'Foo\\TestFieldGroup',
        'file'   => 'stubs/tests/EnumFieldGroup.stub',
        'fields' => [
            [
                'name'         => 'text',
                'label'        => 'Text',
                'key'          => 'field_62c332ac9568a',
            ],
            [
                'name'         => 'number',
                'label'        => 'Number',
                'key'          => 'field_62c332ba9568b',
            ],
            [
                'name'  => 'select',
                'label' => 'Label',
                'key'   => 'field_62c332d39568c',
            ],
        ],
        'enum_names' => [
            'field_62c332ac9568a' => 'TEXT',
            'field_62c332ba9568b' => 'NUMBER',
            'field_62c332d39568c' => 'SELECT',
        ],
        'group' => 'group_62c3329e808ca',
    ];
    $enumFile        = $container->get(FieldEnumBuilder::class)->build($arguments);
    $this->arguments = array_merge($arguments, [
        'enum_file' => $enumFile,
    ]);
    // add this mock to the container
    $container->set(Resolver::class, fn () => $mock->getMock());
    $this->builder   = $container->get(EnumFieldGroupBuilder::class);
});

test('builds expected enum field group class', function () {
    $result = $this->builder->build($this->arguments);
    // file_put_contents(__DIR__ . '/../../../stubs/tests/EnumFieldGroup.stub', $result['content']);
    expect($result['content'])->toBe(file_get_contents(__DIR__ . '/../../../stubs/tests/EnumFieldGroup.stub'));
    expect($result['name'])->toBe('stubs/tests/EnumFieldGroup.stub');
});
