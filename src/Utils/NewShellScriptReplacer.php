<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use Jawira\CaseConverter\Convert;

final class NewShellScriptReplacer implements NewShellScriptReplacerInterface
{
    public function replace(string $shellScript, array $args): string
    {
        $nameConversion   = new Convert($args['name']);
        $vendorConversion = new Convert($args['vendor']);
        $shellScript      = str_replace('project-type', $args['type'], $shellScript);
        $shellScript      = str_replace('project-name', $nameConversion->toKebab(), $shellScript);
        $shellScript      = str_replace('ProjectName', $nameConversion->toPascal(), $shellScript);
        if ($args['host']) {
            $shellScript = str_replace('github.com', $args['host'], $shellScript);
        }

        $shellScript     = str_replace('VendorName', $vendorConversion->toPascal(), $shellScript);
        $shellScript     = str_replace('ViewEngine', $args['view'], $shellScript);

        return $shellScript;
    }
}
