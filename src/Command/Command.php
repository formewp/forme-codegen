<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

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
    protected $filesystem;

    /** @var FilesystemOperator */
    protected $codegenFilesystem;

    /** @var CliMarkdown */
    protected $cliMarkdown;

    /** @var BaseGenerator */
    protected $generator;

    /** @var ClassFinder */
    protected $classFinder;

    /** @var Resolver */
    protected $resolver;

    public function __construct(Filesystem $filesystem, BaseGenerator $generator, ClassFinder $classFinder, Resolver $resolver, CliMarkdown $cliMarkdown, ContainerInterface $container)
    {
        $this->filesystem        = $filesystem;
        $this->cliMarkdown       = $cliMarkdown;
        $this->codegenFilesystem = $container->get('codegenFilesystem');
        $this->generator         = $generator;
        $this->classFinder       = $classFinder;
        $this->resolver          = $resolver;
        parent::__construct();
    }

    protected function help(string $name): string
    {
        return $this->cliMarkdown->render(file_get_contents(__DIR__ . '/../../help/' . $name . '.md'));
    }
}
