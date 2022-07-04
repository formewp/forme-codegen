<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Emgag\Flysystem\TempdirAdapter;
use League\Flysystem\FilesystemOperator;
use PhpParser\Node;
use PhpParser\Node\Stmt\Expression;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitorAbstract;
use PhpParser\Parser;
use PhpParser\ParserFactory;
use Psr\Container\ContainerInterface;

final class FieldGroupResolver
{
    private Parser $parser;

    /** @var FilesystemOperator */
    private $tempFilesystem;

    /** @var TempdirAdapter */
    private $tempFilesystemAdapter;

    public function __construct(ParserFactory $parserFactory, private NodeTraverser $traverser, ContainerInterface $container)
    {
        $this->parser                = $parserFactory->create(ParserFactory::PREFER_PHP7);
        $this->tempFilesystem        = $container->get('tempFilesystem');
        $this->tempFilesystemAdapter = $container->get(TempdirAdapter::class);
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
            $this->tempFilesystem->write('group.php', '<?php return ' . $group . ';');
            $group    = include $this->tempFilesystemAdapter->getPath() . '/group.php';
            $result[] = [$group['key'] => $group['title']];
        }

        return $result;
    }
}
