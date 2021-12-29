<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use League\Flysystem\Filesystem;
use League\Flysystem\FilesystemOperator;
use Psr\Container\ContainerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class BumpCommand extends Command
{
    protected static $defaultName = 'bump';

    /** @var FilesystemOperator */
    private $filesystem;

    /** @var FilesystemOperator */
    private $codegenFilesystem;

    public function __construct(ContainerInterface $container, Filesystem $filesystem)
    {
        $this->codegenFilesystem = $container->get('codegenFilesystem');
        $this->filesystem        = $filesystem;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Bumps the version of a plugin or theme project')
            ->setHelp('Pass in the scope major, minor or patch - defaults to patch')
            ->addArgument('scope', InputArgument::OPTIONAL, 'The scope of the bump - major, minor or patch', 'patch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists('/app/Main.php')) {
            $output->writeln("⛔ <fg=red>This doesn't look like a Forme project directory.</> Try cd-ing into a project first");

            return Command::FAILURE;
        }

        $scope             = $input->getArgument('scope');
        $shellScript       = $this->codegenFilesystem->read('src/Shell/bump.sh');
        $tmpScriptFile     = 'src/Shell/tmp' . uniqid() . '.sh';
        $this->codegenFilesystem->write($tmpScriptFile, $shellScript);
        $process = new Process(['bash', __DIR__ . '/../../' . $tmpScriptFile], null, [
            'BUMP_SCOPE' => $scope,
        ]);
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
        $this->codegenFilesystem->delete($tmpScriptFile);
        if (!$process->isSuccessful()) {
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues.');

            return Command::FAILURE;
        }
        $output->writeln('🎉 <fg=green>Bumped version number successfully!');

        return Command::SUCCESS;
    }
}
