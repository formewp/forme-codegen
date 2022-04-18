<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

final class PostTypeGenerator implements GeneratorInterface
{
    public function __construct(private ClassGenerator $classGenerator)
    {
    }

    public function generate(array $args): array
    {
        return array_merge(
            $this->classGenerator->generate($args),
            $this->classGenerator->generate(['type' => 'model', 'name' => $args['name']]),
        );
    }
}
