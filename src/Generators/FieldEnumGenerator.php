<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Builders\FieldEnumBuilder;
use League\Flysystem\Filesystem;

final class FieldEnumGenerator implements GeneratorInterface
{
    public function __construct(private FieldEnumBuilder $builder, private Filesystem $filesystem)
    {
    }

    public function generate(array $args): array
    {
        // get the field enum class
        $file = $this->builder->build($args);
        // save it to the project enums directory
        $this->filesystem->write($file['name'], $file['content']);
        // todo
        // load up the original field group class file into php parser
        // add a use statement for the enum class
        // replace the field name, key and label string values with the relevant enum values
        // save it back to the original file
        return ['🏆 <fg=green>Generated field enum</> ' . $file['name'], '⚠️ <fg=yellow>NOT YET IMPLEMENTED</> replacing string values with enum values in field group class'];
    }
}
