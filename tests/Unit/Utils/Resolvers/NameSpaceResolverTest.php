<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use Forme\CodeGen\Utils\Resolvers\NameSpaceResolver;
use League\Flysystem\Filesystem;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class NameSpaceResolverTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\NameSpaceResolver
 */
class NameSpaceResolverTest extends TestCase
{
    /**
     * @var NameSpaceResolver
     */
    protected $nameSpaceResolver;

    /**
     * @var Filesystem|Mock
     */
    protected $filesystem;

    /**
     * @var ClassFileResolver|Mock
     */
    protected $fileResolver;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->filesystem        = Mockery::mock(Filesystem::class);
        $this->fileResolver      = Mockery::mock(ClassFileResolver::class);
        $this->nameSpaceResolver = new NameSpaceResolver($this->filesystem, $this->fileResolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->nameSpaceResolver);
        unset($this->filesystem);
        unset($this->fileResolver);
    }

    public function testGet(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }

    public function testGetPlaceHolder(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
