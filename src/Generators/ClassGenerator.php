<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Builders\ClassBuilder;
use League\Flysystem\Filesystem;

final class ClassGenerator implements GeneratorInterface
{
    /** @var Filesystem */
    private $filesystem;

    /** @var ClassBuilder */
    private $classBuilder;

    public function __construct(Filesystem $filesystem, ClassBuilder $classBuilder)
    {
        $this->filesystem    = $filesystem;
        $this->classBuilder  = $classBuilder;
    }

    public function generate(array $args): array
    {
        $type                 = $args['type'];
        $classPrefix          = $args['name'];
        $method               = $args['method'] ?? null;
        $file                 = $this->classBuilder->build($type, $classPrefix, $method);

        $this->filesystem->write($file['name'], $file['content']);
        // careful here, calling class relies on the returned message ending with the class name
        // TODO return an array instead with message and data including filename, plus maybe success/failure status
        return ['🏆 <fg=green>Generated class</> ' . $file['name']];
    }
}
