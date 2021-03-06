<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Builders\MigrationBuilder;
use League\Flysystem\Filesystem;

final class MigrationGenerator implements GeneratorInterface
{
    public function __construct(private Filesystem $filesystem, private MigrationBuilder $migrationBuilder)
    {
    }

    public function generate(array $args): array
    {
        // this is similar to class generator
        // we don't need the type as we know what it is
        $className            = $args['name'];
        $file                 = $this->migrationBuilder->build($className);

        // then let's create the new file in the relevant location
        $this->filesystem->write($file['name'], $file['content']);

        return ['🏆 <fg=green>Generated migration</> ' . $file['name']];
        // return message
    }
}
