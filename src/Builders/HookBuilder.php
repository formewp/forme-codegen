<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Consolidation\Comments\Comments;
use Forme\CodeGen\Utils\YamlFormattingFixer;
use Symfony\Component\Yaml\Yaml;

class HookBuilder
{
    private const DEFAULT_PRIORITY  = 10;
    private const DEFAULT_ARGUMENTS = 1;

    public function __construct(private Comments $commentManager)
    {
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
        $updatedContents  = YamlFormattingFixer::repair($updatedContents);

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
        if (isset($args['method'])) {
            $newEntry['method'] = $args['method'];
        }

        if (isset($args['priority']) && $args['priority'] !== '' && $args['priority'] !== self::DEFAULT_PRIORITY) {
            $newEntry['priority'] = (int) $args['priority'];
        }

        if (isset($args['arguments']) && $args['arguments'] !== '' && $args['arguments'] !== self::DEFAULT_ARGUMENTS) {
            $newEntry['arguments'] = (int) $args['arguments'];
        }

        // pluralise the type and add the entry
        $data[$args['type'] . 's'][] = $newEntry;

        return $data;
    }
}
