<?php

use Forme\CodeGen\Utils\Resolvers\ResolverFactory;
use Forme\CodeGen\Utils\Resolvers\ResolverInterface;
use function Symfony\Component\String\u;

beforeEach(function () {
    $container     = bootstrap();
    $this->factory = $container->get(ResolverFactory::class);
});

test('successfully creates all the existing resolver class files', function () {
    $classFiles = glob(__DIR__ . '/../../../src/Utils/Resolvers/*.php');
    $classes    = array_map(function ($file) {
        return basename($file, '.php');
    }, $classFiles);
    $classes    = array_filter($classes, function ($class) {
        return u($class)->endsWith('Resolver');
    });
    $types = array_map(function ($class) {
        return u($class)->replace('Resolver', '')->snake();
    }, $classes);

    foreach ($types as $type) {
        $class = $this->factory->create($type);
        expect($class)->toBeInstanceOf(ResolverInterface::class);
    }

    expect(count($types))->toBe(count($classes));
});
