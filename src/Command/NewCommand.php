<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Forme\CodeGen\Utils\NewShellScriptReplacer;
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
        $name             = $input->getArgument('name');
        $nameConversion   = new Convert($name);
        $type             = $input->getArgument('type');
        $host             = $input->getOption('host');
        $vendor           = $input->getOption('vendor') ?: 'App';
        $view             = $input->getOption('view') ?: 'plates-4';
        if (!in_array($type, self::VALID_TYPES)) {
            $output->writeln('⛔ <fg=red>Not a valid project type.</> Valid types are: ' . implode(', ', self::VALID_TYPES));

            return Command::FAILURE;
        }

        if (!in_array($view, self::VALID_VIEWS)) {
            $output->writeln('⛔ <fg=red>Not a valid view engine.</> Valid engines are: ' . implode(', ', self::VALID_VIEWS));

            return Command::FAILURE;
        }

        $output->writeln('🌱 Generating new project...');

        $shellScript = $this->codegenFilesystem->read('src/Shell/new_project.sh');
        $replacer    = $this->container->get(NewShellScriptReplacer::class);
        $shellScript = $replacer->replace($shellScript, compact('type', 'name', 'host', 'vendor', 'view'));

        $tmpScriptFile   = 'tmp' . uniqid() . '.sh';
        $this->tempFilesystem->write($tmpScriptFile, $shellScript);
        $tempDirectory  = $this->tempFilesystemAdapter->getPath();
        $process        = new Process(['bash', $tempDirectory . '/' . $tmpScriptFile]);
        $process->setTimeout(null);
        $progressBar = new ProgressBar($output, 80);
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

            return Command::FAILURE;
        }
        $output->writeln('🎉 <fg=green>Created a new Forme ' . $type . ' project!</> You can cd into ' . $nameConversion->toKebab() . '-' . $type . ' and get coding!');

        $notifier     = NotifierFactory::create();
        $notification =
            (new Notification())
                ->setTitle('🎉 Forme Project Created')
                ->setBody('A new Forme ' . $type . ' project has been created!')
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
