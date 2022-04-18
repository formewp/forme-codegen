<?php

declare(strict_types=1);

namespace Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\Resolvers\Resolver;
use Jawira\CaseConverter\Convert;
use Symfony\Component\String\Inflector\InflectorInterface;

final class PlaceholderReplacer implements PlaceholderReplacerInterface
{
    public function __construct(private InflectorInterface $inflector, private Resolver $resolver)
    {
    }

    public function process(string $fileContents, string $type, string $name): string
    {
        $namespacePlaceHolder = $this->resolver->nameSpace()->getPlaceHolder();
        $projectNameSpace     = $this->resolver->nameSpace()->get();
        // replace Placeholders - e.g. the ProjectNameSpace
        $fileContents = str_replace($namespacePlaceHolder, $projectNameSpace, $fileContents);

        // if this is a cpt file, then we need to replace the instances of the name (cptplaceholder or cptplaceholders), with the correct inflections to plural, maintaining the case, and also the translation setting (YOUR-TEXTDOMAIN).
        if ($type === 'post-type') {
            $fileContents = $this->replaceCptPlaceholders($fileContents, $name, $projectNameSpace);
        }
        // if this is a model file then we need to replace the instance of the name in snake_case
        if ($type === 'model') {
            $nameConversion = new Convert($name);
            $fileContents   = str_replace('cpt_placeholder', $nameConversion->toSnake(), $fileContents);
        }

        return $fileContents;
    }

    private function replaceCptPlaceholders(string $fileContents, string $pascalName, string $projectNameSpace): string
    {
        $projectNameSpaceArray   = explode('\\', $projectNameSpace);
        $newTextDomainConversion = new Convert(array_pop($projectNameSpaceArray));
        $newTextDomain           = $newTextDomainConversion->toKebab();
        $nameConversion          = new Convert($pascalName);
        $name                    = $nameConversion->toLower();
        $needle                  = 'cpt placeholders';
        $plural                  = $this->inflector->pluralize($name)[0];
        $fileContents            = $this->replaceSpecialCase($needle, $fileContents, $plural);
        $needle                  = 'cpt placeholder';
        $singular                = $this->inflector->singularize($name)[0];
        $fileContents            = $this->replaceSpecialCase($needle, $fileContents, $singular);
        $fileContents            = str_replace('cpt_placeholder', $nameConversion->toSnake(), $fileContents);
        $fileContents            = str_replace('YOUR-TEXTDOMAIN', $newTextDomain, $fileContents);

        return $fileContents;
    }

    /**
     * Does some special case juggling
     * See https://www.php.net/manual/en/function.str-ireplace.php.
     */
    private function replaceSpecialCase(string $needle, string $haystack, string $replace): string
    {
        $replaceConversion = new Convert($replace);
        preg_match_all("/$needle+/i", $haystack, $matches);
        if (is_array($matches[0]) && count($matches[0]) >= 1) {
            foreach ($matches[0] as $match) {
                // if there is a capital c at the front then let's make this title case, otherwise it's lower
                $replacement = ($match[0] === 'C' ? $replaceConversion->toTitle() : $replaceConversion->toLower());
                $haystack    = str_replace($match, $replacement, $haystack);
            }
        }

        return $haystack;
    }
}
