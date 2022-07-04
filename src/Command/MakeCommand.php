<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Forme\CodeGen\Constants\Files;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

final class MakeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'make';

    /**
     * @var string[]
     */
    private const VALID_TYPES = ['action', 'filter', 'field', 'controller', 'template-controller', 'registry', 'post-type', 'model', 'translator', 'service', 'migration', 'job', 'middleware', 'field-enum'];

    protected function configure(): void
    {
        $this
            ->setDescription('Generates class and other boilerplate in the current working directory')
            ->setHelp($this->help(self::$defaultName))
            ->addArgument('type', InputArgument::REQUIRED, 'The type of class or hook - available types are ' . implode(', ', self::VALID_TYPES))
            ->addArgument('name', InputArgument::REQUIRED, 'This could be the class prefix in PascalCase or the hook reference or custom post type in snake_case')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists(Files::MAIN_CLASS)) {
            $output->writeln("⛔ <fg=red>This doesn't look like a Forme project directory.</> Try cd-ing into a project first");

            return Command::FAILURE;
        }

        $args         = [];
        $args['name'] = $input->getArgument('name');
        $args['type'] = $input->getArgument('type');

        if (!in_array($args['type'], self::VALID_TYPES)) {
            $output->writeln('⛔ <fg=red>This type is not currently supported.</> Available types are ' . implode(', ', self::VALID_TYPES));

            return Command::FAILURE;
        }

        $helper = $this->getHelper('question');
        // the following types need additional interactive args
        // hooks
        if (in_array($args['type'], ['action', 'filter'])) {
            // class, method (optional), priority (optional), arguments (optional), priority (optional)
            $classes  = $this->classFinder->getClasses();
            $question = new ChoiceQuestion(
                '📇 Please select the class that the ' . $args['type'] . ' should call',
                $classes,
                null
            );
            $question->setErrorMessage('That class does not exist');
            $args['class']     = $helper->ask($input, $output, $question);
            $methods           = $this->resolver->classReflection()->getMethods($args['class']);
            $question          = new ChoiceQuestion('🔧 Please select the method name', $methods, '');
            $args['method']    = $helper->ask($input, $output, $question);
            $args['method']    = $args['method'] !== '__invoke' ? $args['method'] : '';
            $question          = new Question('🐇 Please enter the priority (optional, defaults to 10) ', '');
            $args['priority']  = $helper->ask($input, $output, $question);
            $question          = new Question('🔢 Please enter the number of arguments (optional, defaults to 1) ', '');
            $args['arguments'] = $helper->ask($input, $output, $question);
        }

        if ($args['type'] === 'service') {
            $question       = new Question('🔧 Please enter the method name (optional, defaults to handle) ', 'handle');
            $args['method'] = $helper->ask($input, $output, $question);
        }

        if ($args['type'] === 'field-enum') {
            $classes  = $this->classFinder->getClasses('/Fields');
            $question = new ChoiceQuestion(
                '📇 Please select the class that contains the field group to derive the enum from',
                $classes,
                null
            );
            $question->setErrorMessage('That class does not exist');
            $args['class']     = $helper->ask($input, $output, $question);
            $args['file']      = $this->resolver->classReflection()->getFilePath($args['class']);
            // parse the class to get the field group name or names (if multiple)
            $groups = $this->resolver->fieldGroup()->getOptionsFromClassFile($args['file']);

            // if none found, it's an error
            if (empty($groups)) {
                $output->writeln('⛔ <fg=red>No field groups found in the selected class.</>');

                return Command::FAILURE;
            }
            // if multiple, ask the user to select one
            if (count($groups) > 1) {
                $question = new ChoiceQuestion(
                    '📇 Please select the field group to derive the enum from',
                    $groups,
                    null
                );
                $question->setErrorMessage('That field group does not exist');
                $args['group'] = $helper->ask($input, $output, $question);
            } else {
                $args['group'] = $groups[0];
            }
        }

        $messages  = $this->generator->generate($args);

        $output->writeln($messages);

        return Command::SUCCESS;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('type')) {
            $suggestions->suggestValues(self::VALID_TYPES);
        }
    }
}
