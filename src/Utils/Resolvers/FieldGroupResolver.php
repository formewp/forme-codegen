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

        $result = [];
        foreach ($this->visitor->result as $group) {
            $result[] = [$group['key'] => $group['title']];
        }

        return $result;
    }
}
