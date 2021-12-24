<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Jawira\CaseConverter\Convert;
use Psr\Container\ContainerInterface;

final class ResolverFactory
{
    /** @var ContainerInterface */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(string $type): object
    {
        $camel          = (new Convert($type))->toCamel();
        $resolverClass  = __NAMESPACE__ . '\\' . $camel . 'Resolver';

        return $this->container->get($resolverClass);
    }
}
