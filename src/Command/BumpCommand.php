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

#[AsCommand(name: 'bump')]
final class BumpCommand extends Command
{
    /**
     * @var string
     */
    protected static $defaultName = 'bump';

    /**
     * @var string[]
     */
    private const BUMP_TYPES = [
        'major',
        'minor',
        'patch',
    ];

    protected function configure(): void
    {
        $this
            ->setDescription('Bumps the version of a plugin or theme project')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('scope', InputArgument::OPTIONAL, 'The scope of the bump - major, minor or patch', 'patch')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists(Files::MAIN_CLASS)) {
            $output->writeln("⛔ <fg=red>This doesn't look like a Forme project directory.</> Try cd-ing into a project first");

            return Command::FAILURE;
        }

        $scope             = $input->getArgument('scope');

        if (!in_array($scope, self::BUMP_TYPES)) {
            $output->writeln('⛔ <fg=red>Not a valid scope.</> Try major, minor or patch.');

            return Command::FAILURE;
        }

        $scriptFile        = __DIR__ . '/../Shell/bump.sh';
        $process           = new Process(['bash', $scriptFile], null, [
            'BUMP_SCOPE' => $scope,
        ]);
        $success = $this->runProcess($process, $output);
        if (!$success) {
            return Command::FAILURE;
        }

        $output->writeln('🎉 <fg=green>Bumped version number successfully!');

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('scope')) {
            $suggestions->suggestValues(self::BUMP_TYPES);
        }
    }
}
