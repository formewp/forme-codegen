<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassFileResolver as FileResolver;
use League\Flysystem\Filesystem;

final class NameSpaceResolver
{
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
        $mainClassLocation = '/app/Main.php';
        if ($this->filesystem->fileExists($mainClassLocation)) {
            return $this->fileResolver->getNameSpace($mainClassLocation);
        } else {
            return 'Foobar\\ProjectNameSpace';
        }
    }

    /**
     * Get the namespace placeholder used in the source classes.
     */
    public function getPlaceHolder(): string
    {
        return 'NameSpacePlaceHolder';
    }
}
