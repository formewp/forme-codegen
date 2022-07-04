<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\FieldGroupVisitor;
use PhpParser\NodeTraverser;
use PhpParser\Parser;

final class FieldGroupResolver
{
    public function __construct(private Parser $parser, private NodeTraverser $traverser, private FieldGroupVisitor $visitor)
    {
        $this->traverser->addVisitor($this->visitor);
    }

    public function getFromClassFile(string $class): array
    {
        // parse the file with php parser
        $ast= $this->parser->parse(file_get_contents($class));
        // traverse the ast and find acf_add_local_field_group() function calls
        $this->traverser->traverse($ast);

        return $this->visitor->result;
    }

    public function getOptionsFromClassFile(string $class): array
    {
        $result = $this->getFromClassFile($class);

        return array_map(function ($group) {
            return [$group['key'] => $group['title']];
        }, $result);
    }
}
