<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassTypeResolver;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassTypeResolverTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\ClassTypeResolver
 */
class ClassTypeResolverTest extends TestCase
{
    /**
     * @var ClassTypeResolver
     */
    protected $classTypeResolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->classTypeResolver = new ClassTypeResolver();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->classTypeResolver);
    }

    public function testGetSourceClass(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetNamespaceBase(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetTargetDirectory(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
