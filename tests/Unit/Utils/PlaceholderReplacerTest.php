<?php

namespace Tests\Unit\Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\PlaceholderReplacer;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;
use Symfony\Component\String\Inflector\InflectorInterface;

/**
 * Class PlaceholderReplacerTest.
 *
 * @covers \Forme\CodeGen\Utils\PlaceholderReplacer
 */
class PlaceholderReplacerTest extends TestCase
{
    /**
     * @var PlaceholderReplacer
     */
    protected $placeholderReplacer;

    /**
     * @var InflectorInterface|Mock
     */
    protected $inflector;

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

        $this->inflector           = Mockery::mock(InflectorInterface::class);
        $this->resolver            = Mockery::mock(Resolver::class);
        $this->placeholderReplacer = new PlaceholderReplacer($this->inflector, $this->resolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->placeholderReplacer);
        unset($this->inflector);
        unset($this->resolver);
    }

    public function testProcess(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
