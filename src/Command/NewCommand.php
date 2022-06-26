<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Jawira\CaseConverter\Convert;
use Joli\JoliNotif\Notification;
use Joli\JoliNotif\NotifierFactory;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

final class NewCommand extends Command
{
    /**
     * @var string[]
     */
    public const VALID_TYPES = ['theme', 'plugin'];

    /**
     * @var string[]
     */
    public const VALID_VIEWS = ['plates-4', 'blade', 'twig', 'plates'];

    protected static $defaultName = 'new';

    protected function configure(): void
    {
        $this
            ->setDescription('Generates a new forme boilerplate project')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('type', InputArgument::REQUIRED, 'The type of project - plugin or theme')
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the project in Title Case')
            ->addOption('host', null, InputOption::VALUE_REQUIRED, 'The github host name if non standard')
            ->addOption('vendor', null, InputOption::VALUE_REQUIRED, 'The vendor namespace - defaults to App')
            ->addOption('view', null, InputOption::VALUE_REQUIRED, 'The view engine to use - plates-4, blade, twig or plates - defaults to plates-4')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $nameConversion   = new Convert($input->getArgument('name'));
        $type             = $input->getArgument('type');
        $host             = $input->getOption('host');
        $vendor           = $input->getOption('vendor') ?: 'App';
        $view             = $input->getOption('view') ?: 'plates-4';
        $vendorConversion = new Convert($vendor);
        if (!in_array($type, self::VALID_TYPES)) {
            $output->writeln('⛔ <fg=red>Not a valid project type.</> Valid types are: ' . implode(', ', self::VALID_TYPES));

            return Command::FAILURE;
        }

        if (!in_array($view, self::VALID_VIEWS)) {
            $output->writeln('⛔ <fg=red>Not a valid view engine.</> Valid engines are: ' . implode(', ', self::VALID_VIEWS));

            return Command::FAILURE;
        }

        $shellScript = $this->codegenFilesystem->read('src/Shell/new_project.sh');
        $shellScript = str_replace('project-type', $type, $shellScript);
        $shellScript = str_replace('project-name', $nameConversion->toKebab(), $shellScript);
        $shellScript = str_replace('ProjectName', $nameConversion->toPascal(), $shellScript);
        if ($host) {
            $shellScript = str_replace('github.com', $host, $shellScript);
        }

        $shellScript     = str_replace('VendorName', $vendorConversion->toPascal(), $shellScript);
        $shellScript     = str_replace('ViewEngine', $view, $shellScript);

        $tmpScriptFile   = 'src/Shell/tmp' . uniqid() . '.sh';
        $this->codegenFilesystem->write($tmpScriptFile, $shellScript);
        $process = new Process(['bash', __DIR__ . '/../../' . $tmpScriptFile]);
        $process->setTimeout(null);
        $progressBar = new ProgressBar($output);
        $progressBar->start();

        $capturedOutput = '';

        $advance = function ($type, $buffer) use ($progressBar, $capturedOutput) {
            $capturedOutput .= $buffer . PHP_EOL;
            $progressBar->advance();
        };

        $process->run(fn ($type, $buffer) => $advance($type, $buffer));
        $progressBar->finish();
        $this->codegenFilesystem->delete($tmpScriptFile);
        if (!$process->isSuccessful()) {
            $output->writeln($capturedOutput);
            $output->writeln('⛔ <fg=red>Something went wrong.</> You can check the output above for clues. We might have started writing some files into this directory so check and delete as appropriate');

            return Command::FAILURE;
        }

        $output->writeln('🎉 <fg=green>Created a new Forme ' . $type . ' project!</> You can cd into ' . $nameConversion->toKebab() . '-' . $type . ' and get coding!');

        $notifier     = NotifierFactory::create();
        $notification =
            (new Notification())
                ->setTitle('Forme Project Created')
                ->setBody('A new Forme ' . $type . ' project has been created!')
                ->setIcon(__DIR__ . '/../../icon.png')
            ;

        // Send it
        $notifier->send($notification);

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('type')) {
            $suggestions->suggestValues(['plugin', 'theme']);
        }
    }
}
