<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ResolverFactory;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ResolverFactoryTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\ResolverFactory
 */
class ResolverFactoryTest extends TestCase
{
    /**
     * @var ResolverFactory
     */
    protected $resolverFactory;

    /**
     * @var ContainerInterface|Mock
     */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container       = Mockery::mock(ContainerInterface::class);
        $this->resolverFactory = new ResolverFactory($this->container);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->resolverFactory);
        unset($this->container);
    }

    public function testCreate(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
