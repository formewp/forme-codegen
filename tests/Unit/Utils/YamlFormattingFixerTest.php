<?php

namespace Tests\Unit\Forme\CodeGen\Utils;

use Forme\CodeGen\Utils\YamlFormattingFixer;
use PHPUnit\Framework\TestCase;

/**
 * Class YamlFormattingFixerTest.
 *
 * @covers \Forme\CodeGen\Utils\YamlFormattingFixer
 */
class YamlFormattingFixerTest extends TestCase
{
    /**
     * @var YamlFormattingFixer
     */
    protected $yamlFormattingFixer;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        parent::setUp();

        /* @todo Correctly instantiate tested object to use it. */
        $this->yamlFormattingFixer = new YamlFormattingFixer();
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown(): void
    {
        parent::tearDown();

        unset($this->yamlFormattingFixer);
    }

    public function testRepairsWeirdYaml(): void
    {
        $weirdYaml = file_get_contents(__DIR__ . '/../../../stubs/weird-yaml.stub');
        $goodYaml  = file_get_contents(__DIR__ . '/../../../stubs/good-yaml.stub');
        $result    = $this->yamlFormattingFixer->repair($weirdYaml);
        $this->assertEquals($goodYaml, $result);
    }
}
