<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Visitors\FieldGroupVisitor;
use League\Flysystem\Filesystem;
use PhpParser\NodeTraverser;
use PhpParser\Parser;

final class FieldGroupResolver
{
    public function __construct(private Parser $parser, private NodeTraverser $traverser, private FieldGroupVisitor $visitor, private Filesystem $filesystem)
    {
        $this->traverser->addVisitor($this->visitor);
    }

    public function getFromClassFile(string $classFile): array
    {
        // parse the file with php parser
        $contents = $this->filesystem->read($classFile);
        $ast      = $this->parser->parse($contents);
        // traverse the ast and find acf_add_local_field_group() function calls
        $this->traverser->traverse($ast);

        return $this->visitor->result;
    }

    public function getOptionsFromClassFile(string $classFile): array
    {
        $data = $this->getFromClassFile($classFile);

        $result = [];

        foreach ($data as $group) {
            $result[$group['key']] = $group['title'];
        }

        return $result;
    }
}
