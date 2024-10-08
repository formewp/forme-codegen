<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'base')]
final class BaseCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'base';

    /** @var array */
    private const AVAILABLE_COMMANDS = ['new', 'link', 'config', 'install', 'autoload', 'dotenv', 'setup'];

    protected function configure(): void
    {
        $this
            ->setDescription('Base installation utilities')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('baseCommand', InputArgument::REQUIRED, 'E.g. ' . implode(', ', self::AVAILABLE_COMMANDS))
            ->addArgument('args', InputArgument::IS_ARRAY, 'Arguments to pass to the command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $command             = $input->getArgument('baseCommand');
        $args                = $input->getArgument('args');

        if (!in_array($command, self::AVAILABLE_COMMANDS)) {
            $output->writeln('⛔ <fg=red>Available base commands are ' . implode(', ', self::AVAILABLE_COMMANDS) . '.</>');

            return Command::FAILURE;
        }

        if ($command === 'new') {
            return $this->newCommand($args, $output);
        } else {
            if (!$this->filesystem->fileExists('public/wp-config.php')) {
                $output->writeln("⛔ <fg=red>This doesn't look like a WordPress/Forme base directory.</> Try cd-ing into the root directory first");

                return Command::FAILURE;
            }

            if ($command === 'link') {
                return $this->linkCommand($args, $output);
            } else {
                return $this->composerCommand($command, $output);
            }
        }
    }

    protected function newCommand(array $args, OutputInterface $output): int
    {
        // $args[0] is required, error if not set
        if (!isset($args[0])) {
            $output->writeln('⛔ <fg=red>You must specify a project name</>');

            return Command::FAILURE;
        }

        // replace non alpha with hyphens and lowercase the name
        $projectName   = preg_replace('#[^a-zA-Z0-9]#', '-', $args[0]);
        $projectName   = strtolower($projectName);

        // run composer create project
        $process = new Process(['composer', 'create-project', 'forme/base', $projectName]);
        $process->setTty(true);
        $success = $this->runProcess($process, $output);
        if (!$success) {
            return Command::FAILURE;
        }

        $output->writeln('🎉 <fg=green>Base created your new project successfully! </> You can now cd into ' . $projectName . ' and get coding!');

        $notifier     = NotifierFactory::create();
        $notification =
            (new Notification())
                ->setTitle('Forme Project Created')
                ->setBody('A new Forme Base project has been created!')
        ;

        // Send it
        $notifier->send($notification);

        return Command::SUCCESS;
    }

    protected function linkCommand(array $args, OutputInterface $output): int
    {
        // $args[0] is a path to the plugin or theme repo to be linked - we need to get the plugin or theme name from the basename of this too
        $path              = $args[0];
        $pluginOrThemeName = basename($path);
        // $args[1] is the type of link (plugin or theme). if not set, we try to work it out from the path, and if that fails, we assume it's a plugin
        if (!$args[1]) {
            $type = str_contains($path, 'theme') ? 'theme' : 'plugin';
        } else {
            $type = strtolower($args[1]);
        }

        if (!in_array($type, ['plugin', 'theme'])) {
            $output->writeln('⛔ <fg=red>Type of link must be plugin or theme.</>');

            return Command::FAILURE;
        }

        // symlink the plugin or theme to the base directory using ln -s
        $process = new Process(['ln', '-s', $path, getcwd() . '/public/wp-content/' . $type . 's/' . $pluginOrThemeName]);
        $process->run();

        $output->writeln('🎉 <fg=green>Linked ' . $type . ' ' . $pluginOrThemeName . ' successfully!</>');

        return Command::SUCCESS;
    }

    protected function composerCommand(string $command, OutputInterface $output): int
    {
        // map the commands onto the composer script names
        $map = [
            'config'   => 'configure-wordpress',
            'install'  => 'install-wordpress',
            'autoload' => 'require-autoload',
            'dotenv'   => 'init-dot-env',
            'setup'    => 'setup-wordpress',
        ];
        $command = $map[$command];

        $process = new Process(['composer', $command]);
        $process->setTty(true);
        $success = $this->runProcess($process, $output);
        if (!$success) {
            return Command::FAILURE;
        }

        $output->writeln('🎉 <fg=green>' . $command . ' completed successfully!</>');

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('baseCommand')) {
            $suggestions->suggestValues(self::AVAILABLE_COMMANDS);
        }
    }
}
