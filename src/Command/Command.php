<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Forme\CodeGen\Flysystem\TempdirAdapter;
use Forme\CodeGen\Generators\BaseGenerator;
use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use PhpPkg\CliMarkdown\CliMarkdown;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command as SymfonyCommand;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

abstract class Command extends SymfonyCommand
{
    /** @var FilesystemOperator */
    protected $codegenFilesystem;

    /** @var FilesystemOperator */
    protected $tempFilesystem;

    public function __construct(protected Filesystem $filesystem, protected BaseGenerator $generator, protected ClassFinder $classFinder, protected Resolver $resolver, protected CliMarkdown $cliMarkdown, protected TempdirAdapter $tempFilesystemAdapter, protected ContainerInterface $container)
    {
        $this->codegenFilesystem     = $container->get('codegenFilesystem');
        $this->tempFilesystem        = $container->get('tempFilesystem');
        parent::__construct();
    }

    protected function help(string $name): string
    {
        return $this->cliMarkdown->render(file_get_contents(__DIR__ . '/../../help/' . $name . '.md'));
    }

    protected function runProcess(Process $process, OutputInterface $output): bool
    {
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
        if (!$process->isSuccessful()) {
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues.');

            return false;
        }

        return true;
    }

    protected function runProcessWithProgress(Process $process, OutputInterface $output, int $maxSteps = 0): bool
    {
        $process->setTimeout(null);
        $progressBar = new ProgressBar($output, $maxSteps);
        $progressBar->start();

        $capturedOutput = '';
        $advance        = function ($type, $buffer) use ($progressBar, &$capturedOutput) {
            $capturedOutput .= $buffer . PHP_EOL;
            $progressBar->advance();
        };

        $process->run(fn ($type, $buffer) => $advance($type, $buffer));
        $progressBar->finish();
        $output->writeln('');
        if (!$process->isSuccessful()) {
            $output->writeln($capturedOutput);
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues. We might have started writing some files into this directory so check and delete as appropriate');

            return false;
        }

        return true;
    }
}
