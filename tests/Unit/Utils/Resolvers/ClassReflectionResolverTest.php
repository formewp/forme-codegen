<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassReflectionResolver;
use Forme\CodeGen\Utils\Resolvers\NameSpaceResolver;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassReflectionResolverTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\ClassReflectionResolver
 */
class ClassReflectionResolverTest extends TestCase
{
    /**
     * @var ClassReflectionResolver
     */
    protected $classReflectionResolver;

    /**
     * @var NameSpaceResolver|Mock
     */
    protected $nameSpaceResolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->nameSpaceResolver       = Mockery::mock(NameSpaceResolver::class);
        $this->classReflectionResolver = new ClassReflectionResolver($this->nameSpaceResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->classReflectionResolver);
        unset($this->nameSpaceResolver);
    }

    public function testGetUseStatements(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetMethods(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetReflectionClass(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
