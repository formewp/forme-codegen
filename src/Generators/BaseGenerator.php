<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

final class BaseGenerator implements GeneratorInterface
{
    public function __construct(private GeneratorFactoryInterface $factory)
    {
    }

    public function generate(array $args): array
    {
        $generator = $this->factory->create($args['type']);

        return $generator->generate($args);
    }
}
