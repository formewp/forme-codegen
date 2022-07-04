<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

interface ResolverInterface
{
    public function classFile(): ClassFileResolver;

    public function nameSpace(): NameSpaceResolver;

    public function classType(): ClassTypeResolver;

    public function classReflection(): ClassReflectionResolver;

    public function fieldGroup(): FieldGroupResolver;
}
