<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

interface NewShellScriptReplacerInterface
{
    public function replace(string $shellScript, array $args): string;
}
