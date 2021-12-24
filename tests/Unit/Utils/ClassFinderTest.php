<?php

namespace Tests\Unit\Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\ClassFinder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use League\Flysystem\Filesystem;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class ClassFinderTest.
 *
 * @covers \Forme\CodeGen\Utils\ClassFinder
 */
class ClassFinderTest extends TestCase
{
    /**
     * @var ClassFinder
     */
    protected $classFinder;

    /**
     * @var Filesystem|Mock
     */
    protected $filesystem;

    /**
     * @var Resolver|Mock
     */
    protected $resolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem  = Mockery::mock(Filesystem::class);
        $this->resolver    = Mockery::mock(Resolver::class);
        $this->classFinder = new ClassFinder($this->filesystem, $this->resolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->classFinder);
        unset($this->filesystem);
        unset($this->resolver);
    }

    public function testGetClasses(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
