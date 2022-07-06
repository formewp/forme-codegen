<?php

use Forme\CodeGen\Builders\FieldEnumBuilder;

const ARGUMENTS = [
    'name'   => 'FooBar',
    'fields' => [
        [
            'name'         => 'test',
            'label'        => 'Test',
            'key'          => 'EJF95fA9n',
        ],
    ],
    'enum_names' => [
        'EJF95fA9n' => 'TEST',
    ],
];

beforeEach(function () {
    $container     = bootstrap();
    $this->builder = $container->get(FieldEnumBuilder::class);
});

test('builds expected field enum class', function () {
    $result = $this->builder->build(ARGUMENTS);
    // file_put_contents(__DIR__ . '/../../../stubs/tests/FieldEnum.stub', $result['content']);
    expect($result['content'])->toBe(file_get_contents(__DIR__ . '/../../../stubs/tests/FieldEnum.stub'));
    expect($result['name'])->toBe('app/Enums/FooBarField.php');
});
