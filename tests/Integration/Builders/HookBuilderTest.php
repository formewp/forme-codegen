<?php

namespace Tests\Integration\Forme\CodeGen\Builders;

use Consolidation\Comments\Comments;
use Forme\CodeGen\Builders\HookBuilder;
use Forme\CodeGen\Utils\YamlFormattingFixerInterface;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class HookBuilderTest.
 *
 * @covers \Forme\CodeGen\Builders\HookBuilder
 */
class HookBuilderTest extends TestCase
{
    /**
     * @var HookBuilder
     */
    protected $hookBuilder;

    /**
     * @var YamlFormattingFixerInterface|Mock
     */
    protected $fixer;

    /**
     * @var Comments|Mock
     */
    protected $commentManager;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->fixer          = Mockery::mock(YamlFormattingFixerInterface::class);
        $this->commentManager = Mockery::mock(Comments::class);
        $this->hookBuilder    = new HookBuilder($this->fixer, $this->commentManager);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->hookBuilder);
        unset($this->fixer);
        unset($this->commentManager);
    }

    public function testBuild(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
