<?php

use Forme\CodeGen\Utils\Resolvers\FieldGroupResolver;

const MD5_CHECK = '253e597367a043112a65aa9024c43acd';

beforeEach(function () {
    $container      = bootstrap();
    $this->resolver = $container->get(FieldGroupResolver::class);
});

test('gets field groups keys and titles options array from a field group class file', function () {
    $result = $this->resolver->getOptionsFromClassFile('stubs/tests/TestFieldGroup.stub');
    expect($result)->toBe(['group_62c3329e808ca' => 'Test Group']);
});

test('gets an array of field group data from a field group class file', function () {
    $result = $this->resolver->getFromClassFile('/stubs/tests/TestFieldGroup.stub');
    expect($result[0]['key'])->toBe('group_62c3329e808ca');
    expect($result[0]['title'])->toBe('Test Group');
    expect($result[0]['fields'])->toBe([
        [
            'key'     => 'field_62c332ac9568a',
            'label'   => 'Text',
            'name'    => 'text',
        ],
        [
            'key'     => 'field_62c332ba9568b',
            'label'   => 'Number',
            'name'    => 'number',
        ],
        [
            'key'     => 'field_62c332d39568c',
            'label'   => 'Select',
            'name'    => 'select',
        ],
    ]);
    dump(md5(json_encode($result[0]['node'])));

    expect(md5(json_encode($result[0]['node'])))->toBe(MD5_CHECK);
});
