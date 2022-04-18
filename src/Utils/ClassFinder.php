<?php

declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

final class ClassFinder implements ClassFinderInterface
{
    public function __construct(private Filesystem $filesystem, private Resolver $resolver)
    {
    }

    /**
     *  Returns all the classes declared in current directory's app folder.
     */
    public function getClasses(): ?array
    {
        $results = $this->filesystem->listContents('/app', true)
            ->filter(fn(StorageAttributes $attributes) => $attributes->isFile() && str_ends_with($attributes->path(), '.php'))
            ->map(function (StorageAttributes $attributes) {
                $nameSpace = $this->resolver->classFile()->getNameSpace($attributes->path());
                $isCore    = $nameSpace ? (str_ends_with($nameSpace, 'Core')) : false;
                $class     = $this->resolver->classFile()->getClass($attributes->path());
                if ($class && !$isCore) {
                    return $nameSpace . '\\' . $class;
                }
            });

        return $results->toArray() ?: null;
    }
}
