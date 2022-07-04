<?php

namespace Tests\Unit\Forme\CodeGen\Utils\Resolvers;

use Forme\CodeGen\Utils\Resolvers\ClassFileResolver;
use Forme\CodeGen\Utils\Resolvers\ClassReflectionResolver;
use Forme\CodeGen\Utils\Resolvers\ClassTypeResolver;
use Forme\CodeGen\Utils\Resolvers\FieldGroupResolver;
use Forme\CodeGen\Utils\Resolvers\NameSpaceResolver;
use Forme\CodeGen\Utils\Resolvers\Resolver;
use Forme\CodeGen\Utils\Resolvers\ResolverFactory;
use Mockery;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

/**
 * Class ResolverTest.
 *
 * @covers \Forme\CodeGen\Utils\Resolvers\Resolver
 */
class ResolverTest extends TestCase
{
    /**
     * @var Resolver
     */
    protected $resolver;

    /**
     * @var ResolverFactory|Mock
     */
    protected $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->factory  = Mockery::mock(ResolverFactory::class);
        $this->resolver = new Resolver($this->factory);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->resolver);
        unset($this->factory);
    }

    public function testClassFile(): void
    {
        $this->factory->expects()->create('class-file')->andReturn(Mockery::mock(ClassFileResolver::class));
        $result = $this->resolver->classFile();
        $this->assertInstanceOf(ClassFileResolver::class, $result);
        /* @todo This test is incomplete. */
    }

    public function testNameSpace(): void
    {
        $this->factory->expects()->create('name-space')->andReturn(Mockery::mock(NameSpaceResolver::class));
        $result = $this->resolver->nameSpace();
        $this->assertInstanceOf(NameSpaceResolver::class, $result);
    }

    public function testClassType(): void
    {
        $this->factory->expects()->create('class-type')->andReturn(Mockery::mock(ClassTypeResolver::class));
        $result = $this->resolver->classType();
        $this->assertInstanceOf(ClassTypeResolver::class, $result);
    }

    public function testClassReflection(): void
    {
        $this->factory->expects()->create('class-reflection')->andReturn(Mockery::mock(ClassReflectionResolver::class));
        $result = $this->resolver->classReflection();
        $this->assertInstanceOf(ClassReflectionResolver::class, $result);
    }

    public function testFieldGroup(): void
    {
        $this->factory->expects()->create('field-group')->andReturn(Mockery::mock(FieldGroupResolver::class));
        $result = $this->resolver->fieldGroup();
        $this->assertInstanceOf(FieldGroupResolver::class, $result);
    }

    public function testAllResolversHaveMethods(): void
    {
        $resolverDir        = __DIR__ . '/../../../../src/Utils/Resolvers/';
        $resolverFiles      = glob($resolverDir . '*Resolver.php');
        $this->assertNotEmpty($resolverFiles, 'Cannot find resolver class files. Test might be borked or the files have moved');
        $notConfigured      = array_filter($resolverFiles, function ($file) {
            $type = str_replace('Resolver', '', pathinfo($file, PATHINFO_FILENAME));

            return $type && !method_exists($this->resolver, lcfirst($type));
        });
        $this->assertEquals([], $notConfigured, 'Resolver class does not have method for one or more types');
    }
}
