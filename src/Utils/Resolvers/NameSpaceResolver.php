<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Constants\Files;
use Forme\CodeGen\Constants\Placeholders;
use Forme\CodeGen\Utils\Resolvers\ClassFileResolver as FileResolver;
use League\Flysystem\Filesystem;

final class NameSpaceResolver
{
    public const DEFAULT_NAMESPACE = 'Foobar\\ProjectNameSpace';

    public function __construct(private Filesystem $filesystem, private FileResolver $fileResolver)
    {
    }

    /**
     * Get the project name spaces.
     */
    public function get(): string
    {
        // TODO validate
        // our WP projects will have a Main.php class, we can get the name space from that.
        if ($this->filesystem->fileExists(Files::MAIN_CLASS)) {
            return $this->fileResolver->getNameSpace(Files::MAIN_CLASS);
        } else {
            return self::DEFAULT_NAMESPACE;
        }
    }

    /**
     * Get the namespace placeholder used in the source classes.
     */
    public function getPlaceHolder(): string
    {
        return Placeholders::NAMESPACE;
    }
}
