<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ResolverFactory as Factory;

final class Resolver implements ResolverInterface
{
    public function __construct(private Factory $factory)
    {
    }

    public function classFile(): ClassFileResolver
    {
        return $this->factory->create('class-file');
    }

    public function nameSpace(): NameSpaceResolver
    {
        return $this->factory->create('name-space');
    }

    public function classType(): ClassTypeResolver
    {
        return $this->factory->create('class-type');
    }

    public function classReflection(): ClassReflectionResolver
    {
        return $this->factory->create('class-reflection');
    }

    public function fieldGroup(): FieldGroupResolver
    {
        return $this->factory->create('field-group');
    }
}
