<?php

declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use League\Flysystem\StorageAttributes;

final class ClassFinder implements ClassFinderInterface
{
    /** @var Filesystem */
    private $filesystem;

    /** @var Resolver */
    private $resolver;

    public function __construct(Filesystem $filesystem, Resolver $resolver)
    {
        $this->filesystem   = $filesystem;
        $this->resolver     = $resolver;
    }

    /**
     *  Returns all the classes declared in current directory's app folder.
     */
    public function getClasses(): ?array
    {
        $results = $this->filesystem->listContents('/app', true)
            ->filter(function (StorageAttributes $attributes) {
                return $attributes->isFile() && str_ends_with($attributes->path(), '.php');
            })
            ->map(function (StorageAttributes $attributes) {
                $nameSpace = $this->resolver->classFile()->getNameSpace($attributes->path());
                $isCore    = $nameSpace ? (substr($nameSpace, -4) === 'Core') : false;
                $class     = $this->resolver->classFile()->getClass($attributes->path());
                if ($class && !$isCore) {
                    return $nameSpace . '\\' . $class;
                }
            });

        return $results->toArray() ?: null;
    }
}
