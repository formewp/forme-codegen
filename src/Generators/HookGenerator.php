<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Forme\CodeGen\Builders\HookBuilder;
use League\Flysystem\Filesystem;

final class HookGenerator implements GeneratorInterface
{
    /** @var Filesystem */
    private $filesystem;

    /** @var HookBuilder */
    private $hookBuilder;

    public function __construct(Filesystem $filesystem, HookBuilder $hookBuilder)
    {
        $this->filesystem     = $filesystem;
        $this->hookBuilder    = $hookBuilder;
    }

    public function generate(array $args): array
    {
        // the type is action or filter
        // the name is the wp hook ref e.g. init
        // we will also need class, method, priority, arguments
        // First step: read the file, parse the yaml, edit and dump the results.
        $filepath         = '/app/config/hooks.yaml';
        $originalContents = $this->filesystem->read($filepath);

        $result = $this->hookBuilder->build($originalContents, $args);
        $this->filesystem->write($filepath, $result);

        return ['🏆 <fg=green>Updated config</> app/config/hooks.yaml. Tried to maintain comments but check carefully. YMMV'];
    }
}
