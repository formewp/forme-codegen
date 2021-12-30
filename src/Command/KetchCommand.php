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
use Symfony\Component\Yaml\Yaml;

final class KetchCommand extends Command
{
    protected static $defaultName = 'ketch';

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
            ->setDescription('A simple Docker cli for Forme')
            ->setHelp('You can use ketch to configure a new docker container, as well as run simple docker compose commands like up, down, etc. You can also run a selection of commands within the configured container, such as composer, npm, npx and wp')
            ->addArgument('ketchCommand', InputArgument::REQUIRED, 'E.g. init, up, down, composer, npm, wp')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Arguments to pass to the command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists('public/wp-config.php')) {
            $output->writeln("⛔ <fg=red>This doesn't look like a WordPress/Forme base directory.</> Try cd-ing into the root directory first");

            return Command::FAILURE;
        }

        $command             = $input->getArgument('ketchCommand');
        $args                = $input->getArgument('args');
        // if init, we copy over the docker files
        if ($command === 'init') {
            return $this->initCommand($args, $output);
        } elseif ($this->filesystem->fileExists('docker-compose.yml')) {
            if ($command === 'link') {
                return $this->linkCommand($args, $output);
            } else {
                return $this->passThroughCommand($command, $args, $output);
            }
        } else {
            $output->writeln("⛔ <fg=red>It doesn't look like docker has been initialised yet.</> Try running `forme ketch init`");

            return Command::FAILURE;
        }
    }

    protected function initCommand(array $args, OutputInterface $output): int
    {
        $dockerCompose = $this->codegenFilesystem->read('stubs/docker-compose.yml.stub');
        // get the last part of the name of the directory we are in
        if (!$args[0] || $args[0] === '.') {
            $args[0] = basename(getcwd());
        }
        // remove spaces from the name and lowercase it
        $projectName   = strtolower(str_replace(' ', '', $args[0]));
        $dockerCompose = str_replace('{BASE_PROJECT_NAME}', $projectName, $dockerCompose);

        // save it to the project directory
        $this->filesystem->write('docker-compose.yml', $dockerCompose);

        // copy over the docker directory
        $this->filesystem->write('docker/default', $this->codegenFilesystem->read('stubs/docker/default.stub'));
        $this->filesystem->write('docker/Dockerfile', $this->codegenFilesystem->read('stubs/docker/Dockerfile.stub'));
        $this->filesystem->write('docker/php-fpm.conf', $this->codegenFilesystem->read('stubs/docker/php-fpm.conf.stub'));
        $this->filesystem->write('docker/supervisord.conf', $this->codegenFilesystem->read('stubs/docker/supervisord.conf.stub'));

        $output->writeln('🎉 <fg=green>Ketch initialised docker successfully! </> Run `forme ketch up` to start the container.');

        return Command::SUCCESS;
    }

    protected function passThroughCommand(string $command, array $args, OutputInterface $output): int
    {
        $process = new Process(['bash', __DIR__ . '/../../src/Shell/ketch.sh', $command, ...$args]);
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
        if (!$process->isSuccessful()) {
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues.');

            return Command::FAILURE;
        }
        $output->writeln('🎉 <fg=green>Ran ketch command successfully!');

        return Command::SUCCESS;
    }

    protected function linkCommand(array $args, OutputInterface $output): int
    {
        // $args[0] is a path to the plugin or theme repo to be linked - we need to get the plugin or theme name from the basename of this too
        $path              = $args[0];
        $pluginOrThemeName = basename($path);
        // $args[1] is the type of link (plugin or theme). if not set, we try to work it out from the path, and if that fails, we assume it's a plugin
        if (!$args[1]) {
            if (strpos($path, 'theme') !== false) {
                $type = 'theme';
            } else {
                $type = 'plugin';
            }
        } else {
            $type = strtolower($args[1]);
        }

        if (!in_array($type, ['plugin', 'theme'])) {
            $output->writeln('⛔ <fg=red>Type of link must be plugin or theme.</>');

            return Command::FAILURE;
        }

        // open the docker-compose.yml file with symfony yaml component
        $dockerCompose = Yaml::parse($this->filesystem->read('docker-compose.yml'));
        // add the link to the app/volumes array in docker compose
        $dockerCompose['services']['app']['volumes'][] = $path . ':/var/www/html/public/wp-content/' . $type . 's/' . $pluginOrThemeName;
        // dump it back to the filesystem
        $this->filesystem->write('docker-compose.yml', Yaml::dump($dockerCompose));
        $output->writeln('🎉 <fg=green>Linked ' . $type . ' ' . $pluginOrThemeName . ' successfully!</>');

        return Command::SUCCESS;
    }
}
