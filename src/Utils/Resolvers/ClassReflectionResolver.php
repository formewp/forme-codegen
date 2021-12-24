<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Nette\Utils\Reflection;
use ReflectionClass;
use ReflectionMethod;

final class ClassReflectionResolver
{
    /** @var NameSpaceResolver */
    private $nameSpaceResolver;

    public function __construct(NameSpaceResolver $nameSpaceResolver)
    {
        $this->nameSpaceResolver = $nameSpaceResolver;
    }

    /**
     * Get the use statements in a class.
     */
    public function getUseStatements(string $class): array
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
        $dir       = getcwd() . '/app';
        $nameSpace = $this->nameSpaceResolver->get();
        $file      = str_replace($nameSpace, '', $class);
        $file      = str_replace('\\', '/', $file);
        $file      = $dir . $file . '.php';
        require_once $file;

        return new ReflectionClass($class);
    }
}
