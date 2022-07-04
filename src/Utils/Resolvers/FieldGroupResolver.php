<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Emgag\Flysystem\TempdirAdapter;
use Forme\CodeGen\Utils\FieldGroupVisitor;
use League\Flysystem\FilesystemOperator;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use Psr\Container\ContainerInterface;

final class FieldGroupResolver
{
    /** @var FilesystemOperator */
    private $tempFilesystem;

    /** @var TempdirAdapter */
    private $tempFilesystemAdapter;

    public function __construct(private Parser $parser, private NodeTraverser $traverser, ContainerInterface $container, private FieldGroupVisitor $visitor)
    {
        $this->tempFilesystem        = $container->get('tempFilesystem');
        $this->tempFilesystemAdapter = $container->get(TempdirAdapter::class);
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
            $this->tempFilesystem->write('group.php', '<?php return ' . $group . ';');
            $group    = include $this->tempFilesystemAdapter->getPath() . '/group.php';
            $result[] = [$group['key'] => $group['title']];
        }

        return $result;
    }
}
