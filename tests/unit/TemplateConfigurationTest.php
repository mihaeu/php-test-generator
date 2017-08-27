<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\TemplateConfiguration
 */
class TemplateConfigurationTest extends TestCase
{
    /** @var TemplateConfiguration */
    private $templateConfiguration;

    /** @var bool */
    private $php5;

    /** @var bool */
    private $phpunit5;

    /** @var bool */
    private $mockery;

    protected function setUp()
    {
        $this->php5 = false;
        $this->phpunit5 = false;
        $this->mockery = false;
        $this->templateConfiguration = new TemplateConfiguration(
            $this->php5,
            $this->phpunit5,
            $this->mockery
        );
    }

    public function testConvertsToArray()
    {
        assertEquals([
            'php5' => false,
            'phpunit5' => false,
            'mockery' => false,
            'covers' => false,
        ], $this->templateConfiguration->toArray());
    }
}
