<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Builders\EnumFieldGroupBuilder;
use Forme\CodeGen\Builders\FieldEnumBuilder;
use League\Flysystem\Filesystem;
use Symfony\Component\Process\Process;

final class FieldEnumGenerator implements GeneratorInterface
{
    public function __construct(private FieldEnumBuilder $enumBuilder, private Filesystem $filesystem, private EnumFieldGroupBuilder $fieldGroupBuilder)
    {
    }

    public function generate(array $args): array
    {
        // get the field enum class
        $file = $this->enumBuilder->build($args);
        // save it to the project enums directory
        $this->filesystem->write($file['name'], $file['content']);
        // get the field group class
        $args['enum_file'] = $file;
        $fieldFile         = $this->fieldGroupBuilder->build($args);
        // save it to the original file location
        $this->filesystem->write($fieldFile['name'], $fieldFile['content']);
        $fileLocation = getcwd() . '/' . $fieldFile['name'];
        // use ecs cli via symfony process to format the array
        $rootDir = __DIR__ . '/../..';
        $command = [$rootDir . '/tools/ecs', 'check', $fileLocation, '--fix', '--config',  $rootDir . '/array_ecs.php'];
        dump($command);
        $process = new Process($command);
        $process->run();

        return ['🏆 <fg=green>Generated field enum</> ' . $file['name'], '🏆 <fg=green>Updated field group </> ' . $fieldFile['name']];
    }
}
