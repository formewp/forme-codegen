<?php
declare(strict_types=1);

namespace Forme\CodeGen\Utils\Resolvers;

use Jawira\CaseConverter\Convert;
use Psr\Container\ContainerInterface;

final class ResolverFactory
{
    public function __construct(private ContainerInterface $container)
    {
    }

    public function create(string $type): object
    {
        $pascal          = (new Convert($type))->toPascal();
        $resolverClass   = __NAMESPACE__ . '\\' . $pascal . 'Resolver';

        return $this->container->get($resolverClass);
    }
}
