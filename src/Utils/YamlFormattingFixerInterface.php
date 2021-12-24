<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

interface YamlFormattingFixerInterface
{
    public function repair(string $yamlString): string;
}
