<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;

final class FieldGroupResolver
{
    private Parser $parser;

    public function __construct(ParserFactory $parserFactory, private NodeTraverser $traverser)
    {
        $this->parser = $parserFactory->create(ParserFactory::PREFER_PHP7);
    }

    public function getFromClassFile(string $class): array
    {
        // parse the file with php parser
        $ast= $this->parser->parse(file_get_contents($class));
        // add a visitor which finds  acf_add_local_field_group function calls
        $visitor                 = new class() extends NodeVisitorAbstract {
            public array $result = [];

            public function enterNode(Node $node)
            {
                if ($node instanceof Expression) {
                    if ($node->expr->name->parts[0] === 'acf_add_local_field_group') {
                        $argumentNode   = $node->expr->args[0]->value;
                        $prettyPrinter  = new \PhpParser\PrettyPrinter\Standard();
                        $this->result[] = $prettyPrinter->prettyPrintExpr($argumentNode);
                    }

                    return null;
                }
            }
        };
        $this->traverser->addVisitor($visitor);
        // traverse the ast and find acf_add_local_field_group() function calls
        $this->traverser->traverse($ast);

        $result = [];
        foreach ($visitor->result as $group) {
            file_put_contents(__DIR__ . '/test.php', '<?php return ' . $group . ';');
            $group = include __DIR__ . '/test.php';
            unlink(__DIR__ . '/test.php');
            $result[] = [$group['key'] => $group['title']];
        }

        return $result;
    }
}
