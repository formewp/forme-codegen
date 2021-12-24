<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use League\Flysystem\Filesystem;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassFileResolverTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\ClassFileResolver
 */
class ClassFileResolverTest extends TestCase
{
    /**
     * @var ClassFileResolver
     */
    protected $classFileResolver;

    /**
     * @var Filesystem|Mock
     */
    protected $filesystem;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem        = Mockery::mock(Filesystem::class);
        $this->classFileResolver = new ClassFileResolver($this->filesystem);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->classFileResolver);
        unset($this->filesystem);
    }

    public function testGetNameSpace(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetClass(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetClassAndNamespace(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
