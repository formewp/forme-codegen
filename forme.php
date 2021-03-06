<?php

const VERSION = '1.8.6';

// include autoload for local or global context
if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require __DIR__ . '/vendor/autoload.php';
} else {
    require __DIR__ . '/../../../vendor/autoload.php';
}

use Forme\CodeGen\Command\BaseCommand;
use Forme\CodeGen\Command\BumpCommand;
use Forme\CodeGen\Command\KetchCommand;
use Forme\CodeGen\Command\MakeCommand;
use Forme\CodeGen\Command\NewCommand;
use Symfony\Component\Console\Application;

$container = bootstrap();

$application = new Application('Forme Code Generator', VERSION);

$application->add($container->get(MakeCommand::class));
$application->add($container->get(NewCommand::class));
$application->add($container->get(BumpCommand::class));
$application->add($container->get(KetchCommand::class));
$application->add($container->get(BaseCommand::class));

$application->run();
