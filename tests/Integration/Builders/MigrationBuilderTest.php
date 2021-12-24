<?php

namespace Tests\Unit\Forme\CodeGen\Builders;

use Forme\CodeGen\Builders\MigrationBuilder;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Mockery;
use Mockery\Mock;
use Nette\PhpGenerator\PsrPrinter;
use PHPUnit\Framework\TestCase;

/**
 * Class MigrationBuilderTest.
 *
 * @covers \Forme\CodeGen\Builders\MigrationBuilder
 */
class MigrationBuilderTest extends TestCase
{
    /**
     * @var MigrationBuilder
     */
    protected $migrationBuilder;

    /**
     * @var PsrPrinter|Mock
     */
    protected $printer;

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

        $this->printer          = Mockery::mock(PsrPrinter::class);
        $this->resolver         = Mockery::mock(Resolver::class);
        $this->migrationBuilder = new MigrationBuilder($this->printer, $this->resolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->migrationBuilder);
        unset($this->printer);
        unset($this->resolver);
    }

    public function testBuild(): void
    {
        /* @todo This test is incomplete. */
        $this->markTestIncomplete();
    }
}
