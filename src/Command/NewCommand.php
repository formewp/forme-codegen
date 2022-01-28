<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Jawira\CaseConverter\Convert;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class NewCommand extends Command
{
    protected static $defaultName = 'new';

    protected function configure(): void
    {
        $this
            ->setDescription('Generates a new forme boilerplate project')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('type', InputArgument::REQUIRED, 'The type of project - plugin or theme')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project in Title Case')
            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'The github host name if non standard')
            ->addOption('vendor', null, InputOption::VALUE_REQUIRED, 'The vendor namespace if not App')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nameConversion   = new Convert($input->getArgument('name'));
        $type             = $input->getArgument('type');
        $host             = $input->getOption('host');
        $vendor           = $input->getOption('vendor') ?: 'App';
        $vendorConversion = new Convert($vendor);
        if (!in_array($type, ['theme', 'plugin'])) {
            $output->writeln('⛔ <fg=red>Not a valid project type.</> Try plugin or theme.');

            return Command::FAILURE;
        }
        $shellScript = $this->filesystem->read('src/Shell/new_project.sh');
        $shellScript = str_replace('project-type', $type, $shellScript);
        $shellScript = str_replace('project-name', $nameConversion->toKebab(), $shellScript);
        $shellScript = str_replace('ProjectName', $nameConversion->toPascal(), $shellScript);
        if ($host) {
            $shellScript = str_replace('github.com', $host, $shellScript);
        }
        $shellScript     = str_replace('VendorName', $vendorConversion->toPascal(), $shellScript);
        $tmpScriptFile   = 'src/Shell/tmp' . uniqid() . '.sh';
        $this->filesystem->write($tmpScriptFile, $shellScript);
        $process = new Process(['bash', __DIR__ . '/../../' . $tmpScriptFile]);
        $process->setTimeout(null);
        $process->run(function ($type, $buffer) use ($output) {
            $output->writeln($buffer);
        });
        $this->filesystem->delete($tmpScriptFile);
        if (!$process->isSuccessful()) {
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues. We might have started writing some files into this directory so check and delete as appropriate');

            return Command::FAILURE;
        }
        $output->writeln('🎉 <fg=green>Created a new Forme ' . $type . ' project!</> You can cd into ' . $nameConversion->toKebab() . '-' . $type . ' and get coding!');

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('type')) {
            $suggestions->suggestValues(['plugin', 'theme']);
        }
    }
}
