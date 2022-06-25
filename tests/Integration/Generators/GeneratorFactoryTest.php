<?php

namespace Tests\Integration\Forme\CodeGen\Generators;

use Forme\CodeGen\Generators\GeneratorFactory;
use Forme\CodeGen\Generators\GeneratorInterface;
use PHPUnit\Framework\TestCase;
use Psr\Container\ContainerInterface;

/**
 * Class GeneratorFactoryTest.
 *
 * @covers \Forme\CodeGen\Generators\GeneratorFactory
 */
class GeneratorFactoryTest extends TestCase
{
    /**
     * @var GeneratorFactory
     */
    protected $generatorFactory;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->container        = bootstrap();
        $this->generatorFactory = new GeneratorFactory($this->container);
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->generatorFactory);
        unset($this->container);
    }

    public function testCreatesAllConfiguredGeneratorTypes(): void
    {
        $types      = array_keys($this->generatorFactory::STRATEGY_MAP);
        $generators = array_map(function ($type) {
            return $this->generatorFactory->create($type);
        }, $types);
        $this->assertContainsOnlyInstancesOf(GeneratorInterface::class, $generators);
    }

    public function testCorrectGeneratorTypesAreConfigured(): void
    {
        $generatorDir        = __DIR__ . '/../../../src/Generators/';
        $configuredTypes     = $this->generatorFactory::STRATEGY_MAP;
        $generatorFiles      = glob($generatorDir . '*Generator.php');
        $this->assertNotEmpty($generatorFiles, 'Cannot find generator class files. Test might be borked or the files have moved');
        $notFiles            = array_filter($configuredTypes, function ($type) use ($generatorDir, $generatorFiles) {
            return !in_array($generatorDir . $type . 'Generator.php', $generatorFiles);
        });
        $notConfigured = array_filter($generatorFiles, function ($file) use ($configuredTypes) {
            $type = str_replace('Generator', '', pathinfo($file, PATHINFO_FILENAME));

            return !in_array($type, $configuredTypes) && $type !== 'Base';
        });
        $this->assertEquals([], array_merge($notConfigured, $notFiles), 'Correct generator types are not not configured in generator factory.');
    }
}
