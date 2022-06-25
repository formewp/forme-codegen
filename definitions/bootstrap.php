<?php

// Instantiate PHP-DI Container

use DI\ContainerBuilder;

function bootstrap()
{
    $builder = new ContainerBuilder();
    $builder->addDefinitions(dependencies());

    return $builder->build();
}
