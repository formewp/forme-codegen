<?php
declare(strict_types=1);

namespace Forme\CodeGen\Command\Question\Make;

use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Jawira\CaseConverter\Convert;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

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
        $groupOptions = $this->resolver->fieldGroup()->getOptionsFromClassFile($args['file']);

        // if none found, it's an error
        if (empty($groupOptions)) {
            $output->writeln('⛔ <fg=red>No field groups found in the selected class.</>');

            return null;
        }
        // if multiple, ask the user to select one
        if (count($groupOptions) > 1) {
            $question = new ChoiceQuestion(
                '📇 Please select the field group to derive the enum from',
                $groupOptions,
                null
            );
            $question->setErrorMessage('That field group does not exist');
            $args['group'] = $helper->ask($input, $output, $question);
        } else {
            // get the first key in the array
            $args['group'] = array_keys($groupOptions)[0];
        }
        $groupData      = $this->resolver->fieldGroup()->getFromClassFile($args['file']);
        $groupData      = array_filter($groupData, function ($group) use ($args) {
            return $group['key'] === $args['group'];
        });
        $args['fields'] = $groupData[0]['fields'];

        // we now need to cycle through the fields and ask the user to select an enum name for each one. We will suggest the field label in CONST_CASE. If not unique, we will append a number to the end.

        $output->writeln('📇 <fg=green>Please select an enum name for each field in the field group.</>');
        $output->writeln('These must be in CONSTANT_CASE.');
        $enumNameChoices = [];
        foreach ($args['fields'] as $field) {
            // ask the user to type an enum name for this field, we suggest the CONT_CASE of the field label
            $suggestion = (new Convert($field['label']))->toMacro();
            if (in_array($suggestion, $enumNameChoices)) {
                $threeRandomCapitalLetters = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 3);
                $suggestion .= '_' . $threeRandomCapitalLetters;
            }
            $question   = new Question(
                '✨ Field <fg=yellow>' . $field['label'] . '</> [' . $suggestion . '] : ',
                $suggestion
            );
            $question->setValidator(function ($answer) use ($enumNameChoices) {
                if (in_array($answer, $enumNameChoices)) {
                    throw new \RuntimeException('That enum name is already taken. Please choose another.');
                }

                return $answer;
            });
            // validate constant case
            $question->setValidator(function ($answer) {
                if (!preg_match('/^[A-Z_]+$/', $answer)) {
                    throw new \RuntimeException('That is not a valid enum name. You must use CONSTANT_CASE');
                }

                return $answer;
            });
            $enumNameChoices[$field['key']] = $helper->ask($input, $output, $question);
        }
        $args['enum_names'] = $enumNameChoices;

        return $args;
    }
}
