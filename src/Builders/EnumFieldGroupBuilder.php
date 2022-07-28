<?php
declare(strict_types=1);

namespace Forme\CodeGen\Builders;

use Emgag\Flysystem\TempdirAdapter;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Forme\CodeGen\Visitors\FieldGroupReplaceVisitor;
use League\Flysystem\FilesystemOperator;
use Nette\PhpGenerator\ClassType;
use Nette\PhpGenerator\PhpFile;
use Nette\PhpGenerator\PsrPrinter;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PhpParser\PrettyPrinter\Standard;
use Psr\Container\ContainerInterface;
use Symfony\Component\Process\Process;

final class EnumFieldGroupBuilder
{
    private FilesystemOperator $tempFilesystem;

    public function __construct(private Parser $parser, private NodeTraverser $traverser, private FieldGroupReplaceVisitor $visitor, private Standard $printer, private Resolver $resolver, private PsrPrinter $nettePrinter, ContainerInterface $container, private TempdirAdapter $tempFilesystemAdapter)
    {
        $this->traverser->addVisitor($this->visitor);
        $this->tempFilesystem = $container->get('tempFilesystem');
    }

    public function build(array $args): array
    {
        // load up the original field group class file into php parser
        $ast= $this->parser->parse(file_get_contents($args['file']));
        // use the field group replace visitor to replace the field name, key and label string values with the relevant enum values
        $this->visitor->set($args);
        $ast      = $this->traverser->traverse($ast);
        $contents = $this->printer->prettyPrintFile($ast);
        // pass it through nette, being careful to preserve namespace and use statements
        $class = ClassType::fromCode($contents);
        $class->setFinal();
        $file          = new PhpFile();
        $file->addComment('This file has been auto-amended.');
        $file->setStrictTypes(); // adds declare(strict_types=1)

        $namespace     = $file->addNamespace($this->resolver->classFile()->getNameSpace($args['file']));
        $useStatements = $this->resolver->classReflection()->getUseStatements($args['class']);
        foreach ($useStatements as $alias => $use) {
            $namespace->addUse($use, $alias);
        }
        // add a use statement for the enum class
        $namespace->addUse($args['enum_file']['class']->getNamespace()->getName() . '\\' . $args['enum_file']['class']->getName(), $args['enum_file']['class']->getName());
        $namespace->add($class);

        $contents = $this->nettePrinter->printFile($file);

        // save the file to a temporary location
        $this->tempFilesystem->write($args['file'], $contents);

        $fileLocation = $this->tempFilesystemAdapter->getPath() . '/' . $args['file'];
        // use ecs cli via symfony process to format the array
        $rootDir     = __DIR__ . '/../..';
        $ecsCommand  = $rootDir . '/tools/ecs';
        if (!file_exists($ecsCommand)) {
            // try global
            $process = new Process(['composer', '-n', 'config', '--global', 'home']);
            $process->run();
            $ecsCommand = $process->getOutput() . '/vendor/bin/ecs';
        }
        $command = [$ecsCommand, 'check', $fileLocation, '--fix', '--config',  $rootDir . '/array_ecs.php'];
        $process = new Process($command);
        $process->run();

        // load the formatted file back into contents
        $contents = $this->tempFilesystem->read($args['file']);

        return [
            'content'  => $contents,
            'name'     => $args['file'],
            'class'    => array_values($namespace->getClasses())[0],
        ];
    }
}
