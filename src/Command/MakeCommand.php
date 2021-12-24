<?php

declare(strict_types=1);

namespace Forme\CodeGen\Command;

use Forme\CodeGen\Generators\BaseGenerator;
use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Question\Question;

final class MakeCommand extends Command
{
    // the name of the command (the part after "bin/console")
    protected static $defaultName = 'make';

    private const VALID_TYPES = ['action', 'filter', 'field', 'controller', 'template-controller', 'registry', 'post-type', 'model', 'translator', 'service', 'migration', 'job', 'middleware'];

    /** @var Filesystem */
    private $filesystem;

    /** @var BaseGenerator */
    private $generator;

    /** @var ClassFinder */
    private $classFinder;

    /** @var Resolver */
    private $resolver;

    public function __construct(Filesystem $filesystem, BaseGenerator $generator, ClassFinder $classFinder, Resolver $resolver)
    {
        $this->filesystem   = $filesystem;
        $this->generator    = $generator;
        $this->classFinder  = $classFinder;
        $this->resolver     = $resolver;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Generates class and other boilerplate in the current working directory')
            ->setHelp('Pass in the name and type')
            ->addArgument('type', InputArgument::REQUIRED, 'The type of class or hook - see documentation for available options')
            ->addArgument('name', InputArgument::REQUIRED, 'This could be the class prefix in PascalCase or the hook reference or custom post type in snake_case')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->filesystem->fileExists('/app/Main.php')) {
            $output->writeln("⛔ <fg=red>This doesn't look like a Forme project directory.</> Try cd-ing into a project first");

            return Command::FAILURE;
        }

        $args         = [];
        $args['name'] = $input->getArgument('name');
        $args['type'] = $input->getArgument('type');

        if (!in_array($args['type'], self::VALID_TYPES)) {
            $output->writeln('⛔ <fg=red>This type is not currently supported.</> Available types are ' . implode("\n", self::VALID_TYPES));

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

        $messages  = $this->generator->generate($args);

        $output->writeln($messages);

        return Command::SUCCESS;
    }
}
