<?php

declare(strict_types=1);

namespace Forme\CodeGen\Utils;

final class YamlFormattingFixer implements YamlFormattingFixerInterface
{
    /**
     * This repairs Symfony Yaml's sub-optimal formatting of multi line arrays
     * If we didn't do this the comments wouldn't be injected back in as the lines won't match.
     */
    public function repair(string $yamlString): string
    {
        $lines  = explode("\n", $yamlString);
        $result = [];
        $flag   = false;
        foreach ($lines as $line) {
            if ($line === '  -') {
                $flag = true;
                continue;
            }

            if ($flag) {
                $result[] = '  -' . substr($line, 3);
                $flag     = false;
            } else {
                $result[] = $line;
            }
        }

        return implode("\n", $result);
    }
}
