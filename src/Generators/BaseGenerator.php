<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

final class BaseGenerator implements GeneratorInterface
{
    /** @var GeneratorFactoryInterface */
    private $factory;

    public function __construct(GeneratorFactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    public function generate(array $args): array
    {
        $generator = $this->factory->create($args['type']);

        return $generator->generate($args);
    }
}
