<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Consolidation\Comments\Comments;
use Forme\CodeGen\Utils\YamlFormattingFixerInterface;
use Symfony\Component\Yaml\Yaml;

class HookBuilder
{
    /** @var Comments */
    private $commentManager;

    /** @var YamlFormattingFixerInterface */
    private $fixer;

    public function __construct(YamlFormattingFixerInterface $fixer, Comments $commentManager)
    {
        $this->commentManager = $commentManager;
        $this->fixer          = $fixer;
    }

    public function build(string $originalContents, array $args): string
    {
        // the type is action or filter
        // the name is the wp hook ref e.g. init
        // we will also need class, method, priority, arguments
        // First step: read the file, parse the yaml, edit and dump the results.
        $parsedData       = Yaml::parse($originalContents);
        $processedData    = $this->process($parsedData, $args);
        $updatedContents  = Yaml::dump($processedData, PHP_INT_MAX, 2);
        $updatedContents  = $this->fixer->repair($updatedContents);

        // Second step: collect comments from original document and inject them into result.
        $this->commentManager->collect(explode("\n", $originalContents));
        $updatedWithComments = $this->commentManager->inject(explode("\n", $updatedContents));

        return implode("\n", $updatedWithComments);
    }

    private function process(array $data, array $args): array
    {
        $newEntry = [
            'hook'  => $args['name'],
            'class' => $args['class'],
        ];
        if ($args['method']) {
            $newEntry['method'] = $args['method'];
        }
        if ($args['priority']) {
            $newEntry['priority'] = intval($args['priority']);
        }
        if ($args['arguments']) {
            $newEntry['arguments'] = intval($args['arguments']);
        }
        // pluralise the type and add the entry
        $data[$args['type'] . 's'][] = $newEntry;

        return $data;
    }
}
