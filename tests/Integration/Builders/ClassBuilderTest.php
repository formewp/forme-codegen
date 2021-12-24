<?php

namespace Tests\Unit\Forme\CodeGen\Builders;

use Forme\CodeGen\Builders\ClassBuilder;
use Forme\CodeGen\Utils\PlaceholderReplacerInterface;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Mockery\Mock;
use Nette\PhpGenerator\PsrPrinter;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class ClassBuilderTest.
 *
 * @covers \Forme\CodeGen\Builders\ClassBuilder
 */
class ClassBuilderTest extends TestCase
{
    /**
     * This needs to be updated if the rendered controller file contents change.
     */
    private const MD5_CHECK = '60981f8fd04dd459a981c98bca3beb23';

    /**
     * @var ClassBuilder
     */
    protected $classBuilder;

    /**
     * @var PsrPrinter|Mock
     */
    protected $printer;

    /**
     * @var PlaceholderReplacerInterface|Mock
     */
    protected $replacer;

    /**
     * @var Resolver|Mock
     */
    protected $resolver;

    /** @var ContainerInterface */
    protected $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->container        = include __DIR__ . '/../../../definitions/bootstrap.php';
        $this->printer          = $this->container->get(PsrPrinter::class);
        $this->replacer         = $this->container->get(PlaceholderReplacerInterface::class);
        $this->resolver         = $this->container->get(Resolver::class);
        $this->classBuilder     = new ClassBuilder($this->printer, $this->replacer, $this->resolver);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->classBuilder);
        unset($this->printer);
        unset($this->replacer);
        unset($this->resolver);
    }

    public function testBuildsExpectedControllerClass(): void
    {
        $result = $this->classBuilder->build('controller', 'FooBar');
        dump(md5($result['content']));
        $this->assertEquals('app/Controllers/FooBarController.php', $result['name']);
        $this->assertEquals(self::MD5_CHECK, md5($result['content']));
    }
}
