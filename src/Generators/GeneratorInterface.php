<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

interface GeneratorInterface
{
    public function generate(array $args): array;
}
