<?php
declare(strict_types=1);

namespace Forme\CodeGen\Command\Question\Make;

use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

final class FieldEnumQuestions
{
    public function __construct(private ClassFinder $classFinder, private Resolver $resolver)
    {
    }

    public function ask(array $args, QuestionHelper $helper, InputInterface $input, OutputInterface $output): ?array
    {
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

            return null;
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

        return $args;
    }
}
