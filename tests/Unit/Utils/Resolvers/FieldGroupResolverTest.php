<?php

use Forme\CodeGen\Utils\Resolvers\FieldGroupResolver;

beforeEach(function () {
    $container      = bootstrap();
    $this->resolver = $container->get(FieldGroupResolver::class);
});

test('gets field groups names from a field group class file', function () {
    $result = $this->resolver->getFromClassFile(__DIR__ . '/../../../../stubs/tests/TestFieldGroup.php');
    expect($result)->toBe([['group_62c3329e808ca' => 'Test Group']]);
});
