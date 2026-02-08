<?php
declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Forme\CodeGen\Constants\Files;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

#[AsCommand(name: 'test')]
final class TestCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'test';

    /**
     * @var string[]
     */
    private const SUGGESTED_COMMANDS = ['run', 'server', 'setup'];

    protected function configure(): void
    {
        $this
            ->setDescription('Testing helpers for plugin and theme projects.')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('testCommand', null, 'E.g. ' . implode(', ', self::SUGGESTED_COMMANDS), 'run')
            ->addArgument('args', InputArgument::IS_ARRAY, 'Arguments to pass to the command')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists(Files::MAIN_CLASS)) {
            $output->writeln("⛔ <fg=red>This doesn't look like a Forme project directory.</> Try cd-ing into a project first");

            return Command::FAILURE;
        }

        $command             = $input->getArgument('testCommand');
        $args                = $input->getArgument('args');

        if ($command === 'run') {
            return $this->runCommand($output);
        } elseif ($command === 'server') {
            return $this->serverCommand($args, $output);
        } else {
            return $this->setupCommand($output);
        }
    }

    protected function runCommand(OutputInterface $output): int
    {
        if (!$this->filesystem->directoryExists(Files::TEST_DIR)) {
            $output->writeln('⛔ <fg=red>The wp-test directory does not exist. You should run `forme test setup` first');

            return Command::FAILURE;
        }

        // run pest
        $process = new Process(['./tools/pest']);
        $process->setTty(true);
        $success = $this->runProcess($process, $output);
        if (!$success) {
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    protected function serverCommand(array $args, OutputInterface $output): int
    {
        $commandType = $args[0];

        if (!in_array($commandType, ['start', 'stop'])) {
            $output->writeln('⛔ <fg=red>Not a valid server command.</> Try start or stop.');

            return Command::FAILURE;
        }

        if (!$this->filesystem->directoryExists(Files::TEST_DIR)) {
            $output->writeln('⛔ <fg=red>The wp-test directory does not exist. You should run `forme test setup` before running any server commands');

            return Command::FAILURE;
        }

        if ($commandType === 'start') {
            $port              = $args[1] ?? 8000;
            $shellScript = $this->codegenFilesystem->read('src/Shell/server_start.sh');
            if ($port !== 8000) {
                // add the port to the script
                $shellScript = str_replace('8000', $port, $shellScript);
            }
            $scriptFile        = 'tmp' . uniqid() . '.sh';

            $this->tempFilesystem->write($scriptFile, $shellScript);
            $scriptFile        = $this->tempFilesystemAdapter->getPath() . '/' . $scriptFile;
        } else {
            $scriptFile        = __DIR__ . '/../Shell/server_stop.sh';
        }

        $process           = new Process(['bash', $scriptFile]);
        $success           = $this->runProcess($process, $output);
        if (!$success) {
            return Command::FAILURE;
        }

        if ($commandType === 'start') {
            $output->writeln("🎉 <fg=green>Started the test server successfully! You can visit http://localhost:{$port} in your browser");
        } else {
            $output->writeln('🎉 <fg=green>Stopped the test server successfully!');
        }

        return Command::SUCCESS;
    }

    protected function setupCommand(OutputInterface $output): int
    {
        if ($this->filesystem->directoryExists(Files::TEST_DIR)) {
            $output->writeln('⛔ <fg=red>The wp-test directory already exists. Please delete it first if you want to create a new one');

            return Command::FAILURE;
        }

        // get the current path and get the plugin or theme name from the basename
        $path              = getcwd();
        $projectName       = basename($path);
        // try to work out the project type from the path, and if that fails, assume it's a plugin but spit out a warning
        $projectType = str_contains($path, 'theme') ? 'theme' : 'plugin';

        if ($projectType === 'plugin' && !str_contains($path, 'theme') && !str_contains($path, 'plugin')) {
            $output->writeln('⚠️ <fg=orange>Couldn\'t figure out if this is a plugin or a theme project, defaulting to plugin.');
        }

        $output->writeln('🧪 Setting up a new test instance for this project...');

        $dbFile     = $this->codegenFilesystem->read('stubs/wp-test/db.php.stub');
        $serverFile = $this->codegenFilesystem->read('stubs/wp-test/server.php.stub');

        $this->tempFilesystem->write('db.php', $dbFile);
        $this->tempFilesystem->write('server.php', $serverFile);

        $scriptFile        = __DIR__ . '/../Shell/test_setup.sh';
        $process           = new Process(['bash', $scriptFile], null, [
            'PROJECT_TYPE' => $projectType,
            'PROJECT_NAME' => $projectName,
            'TMP_DIR'      => $this->tempFilesystemAdapter->getPath(),
        ]);
        $success = $this->runProcessWithProgress($process, $output, 120);
        if (!$success) {
            return Command::FAILURE;
        }

        $output->writeln('🎉 <fg=green>Set up test instance successfully! It\'s in `wp-test`');

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('testCommand')) {
            $suggestions->suggestValues(self::SUGGESTED_COMMANDS);
        }
    }
}
