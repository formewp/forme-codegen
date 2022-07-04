<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

interface ClassFinderInterface
{
    public function getClasses(?string $directory = ''): ?array;
}
