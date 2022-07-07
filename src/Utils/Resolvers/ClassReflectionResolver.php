<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Nette\Utils\Reflection;
use ReflectionClass;
use ReflectionMethod;

final class ClassReflectionResolver
{
    public function __construct(private NameSpaceResolver $nameSpaceResolver)
    {
    }

    /**
     * Get the use statements in an app class.
     */
    public function getUseStatements(string $class): array
    {
        $rc = $this->getReflectionClass($class);

        return Reflection::getUseStatements($rc);
    }

    /**
     * Get the use statements in an app class.
     */
    public function getUseStatementsFromLoadedClass(string $class): array
    {
        $rc = new ReflectionClass($class);

        return Reflection::getUseStatements($rc);
    }

    /**
     * Get class methods from an app file.
     */
    public function getMethods(string $class): array
    {
        $rc                = $this->getReflectionClass($class);
        $reflectionMethods = $rc->getMethods(ReflectionMethod::IS_PUBLIC);
        $result            = [];
        foreach ($reflectionMethods as $reflectionMethod) {
            $result[] = $reflectionMethod->name;
        }

        return $result;
    }

    /**
     * Require a class and get reflection.
     */
    public function getReflectionClass(string $class): object
    {
        $file = $this->getFilePath($class);
        require_once $file;

        return new ReflectionClass($class);
    }

    /**
     * get the file path of a class.
     */
    public function getFilePath(string $class): string
    {
        $dir       = getcwd() . '/app';
        $nameSpace = $this->nameSpaceResolver->get();
        $file      = str_replace($nameSpace, '', $class);
        $file      = str_replace('\\', '/', $file);
        $file      = $dir . $file . '.php';

        return $file;
    }

    /**
     * Get the file path of a class relative to the project root.
     */
    public function getRelativeFilePath(string $class): string
    {
        $dir      = getcwd() . '/';
        $absolute = $this->getFilePath($class);

        return str_replace($dir, '', $absolute);
    }
}
