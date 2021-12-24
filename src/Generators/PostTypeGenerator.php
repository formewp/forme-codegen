<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

final class PostTypeGenerator implements GeneratorInterface
{
    /** @var ClassGenerator */
    private $classGenerator;

    public function __construct(ClassGenerator $classGenerator)
    {
        $this->classGenerator = $classGenerator;
    }

    public function generate(array $args): array
    {
        return array_merge(
            $this->classGenerator->generate($args),
            $this->classGenerator->generate(['type' => 'model', 'name' => $args['name']]),
        );
    }
}
