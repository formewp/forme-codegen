<?php

use Emgag\Flysystem\TempdirAdapter;
use Forme\CodeGen\Generators\GeneratorFactory;
use Forme\CodeGen\Generators\GeneratorFactoryInterface;
use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\ClassFinderInterface;
use Forme\CodeGen\Utils\PlaceholderReplacer;
use Forme\CodeGen\Utils\PlaceholderReplacerInterface;
use Forme\CodeGen\Utils\YamlFormattingFixer;
use Forme\CodeGen\Utils\YamlFormattingFixerInterface;
use League\Flysystem\Filesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;
use Symfony\Component\String\Inflector\EnglishInflector;
use Symfony\Component\String\Inflector\InflectorInterface;

if (!function_exists('dependencies')) {
    function dependencies(): array
    {
        return [
            LocalFilesystemAdapter::class => function () {
                return new LocalFilesystemAdapter(getcwd());
            },
            Filesystem::class => function (LocalFilesystemAdapter $adapter) {
                return new Filesystem($adapter);
            },
            TempdirAdapter::class => fn () => new TempdirAdapter(),
            'tempFilesystem'      => function (TempdirAdapter $adapter) {
                return new Filesystem($adapter);
            },
            'codegenFilesystem'   => function () {
                $adapter = new LocalFilesystemAdapter(__DIR__ . '/..');

                return new Filesystem($adapter);
            },
            InflectorInterface::class           => DI\get(EnglishInflector::class),
            GeneratorFactoryInterface::class    => DI\get(GeneratorFactory::class),
            ClassFinderInterface::class         => DI\get(ClassFinder::class),
            PlaceholderReplacerInterface::class => DI\get(PlaceholderReplacer::class),
            YamlFormattingFixerInterface::class => DI\get(YamlFormattingFixer::class),
        ];
    }
}
