<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

interface PlaceholderReplacerInterface
{
    public function process(string $fileContents, string $type, string $name): string;
}
