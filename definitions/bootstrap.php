<?php

// Instantiate PHP-DI Container

use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(include 'dependencies.php');
$container = $builder->build();

return $container;
