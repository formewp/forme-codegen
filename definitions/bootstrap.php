<?php

// Instantiate PHP-DI Container

use DI\Container;
use DI\ContainerBuilder;

if (!function_exists('bootstrap')) {
    function bootstrap(): Container
    {
        $builder = new ContainerBuilder();
        $builder->addDefinitions(dependencies());

        return $builder->build();
    }
}
