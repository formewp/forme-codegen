<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Emgag\Flysystem\TempdirAdapter;
use Forme\CodeGen\Generators\BaseGenerator;
use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use PhpPkg\CliMarkdown\CliMarkdown;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;

abstract class Command extends SymfonyCommand
{
    /** @var FilesystemOperator */
    protected $codegenFilesystem;

    /** @var FilesystemOperator */
    protected $tempFilesystem;

    public function __construct(protected Filesystem $filesystem, protected BaseGenerator $generator, protected ClassFinder $classFinder, protected Resolver $resolver, protected CliMarkdown $cliMarkdown, protected TempDirAdapter $tempFilesystemAdapter, protected ContainerInterface $container)
    {
        $this->codegenFilesystem     = $container->get('codegenFilesystem');
        $this->tempFilesystem        = $container->get('tempFilesystem');
        parent::__construct();
    }

    protected function help(string $name): string
    {
        return $this->cliMarkdown->render(file_get_contents(__DIR__ . '/../../help/' . $name . '.md'));
    }
}
