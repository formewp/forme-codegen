<?php
declare(strict_types=1);

namespace Forme\CodeGen\Generators;

interface GeneratorFactoryInterface
{
    public function create(string $type): GeneratorInterface;
}
