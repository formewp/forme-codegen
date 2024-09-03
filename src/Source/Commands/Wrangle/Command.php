<?php

declare(strict_types=1);

namespace Forme\CodeGen\Source\Commands\Wrangle;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

use function Laravel\Prompts\info;

final class Command extends BaseCommand
{
    protected static $defaultName = 'foo';

    protected function configure(): void
    {
        // add input, descriptions and help text here - see  https://symfony.com/doc/current/console/input.html and https://symfony.com/doc/current/console.html#configuring-the-command
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // you have access to laravel/prompts as well as core Symfony Console helpers, you can mix and match
        info('foo');

        return BaseCommand::SUCCESS;
    }
}
