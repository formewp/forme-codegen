<?php

declare(strict_types=1);

namespace Forme\CodeGen\Generators;

use Psr\Container\ContainerInterface;

final class GeneratorFactory implements GeneratorFactoryInterface
{
    public const STRATEGY_MAP = [
        'action'      => 'Hook',
        'filter'      => 'Hook',
        'migration'   => 'Migration',
        'post-type'   => 'PostType',
        'registry'    => 'Registry',
        'default'     => 'Class',
    ];

    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $type): GeneratorInterface
    {
        $strategy       = self::STRATEGY_MAP[$type] ?? self::STRATEGY_MAP['default'];
        $generatorClass = __NAMESPACE__ . '\\' . $strategy . \Generator::class;
        /* @var GeneratorInterface */
        return $this->container->get($generatorClass);
    }
}
