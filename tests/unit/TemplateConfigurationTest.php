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

    /** @var Clazz */
    private $clazz;

    /** @var bool */
    private $php5;

    /** @var bool */
    private $phpunit5;

    /** @var bool */
    private $mockery;

    /** @var bool */
    private $covers;

    protected function setUp()
    {
        $this->clazz = new Clazz('Test', 'Test', '');
        $this->php5 = false;
        $this->phpunit5 = false;
        $this->mockery = false;
        $this->templateConfiguration = new TemplateConfiguration(
            $this->clazz,
            $this->php5,
            $this->phpunit5,
            $this->mockery,
            $this->covers
        );
    }

    public function testConvertsToArray()
    {
        assertEquals([
            'baseClass' => [
                'class' => 'Test',
                'namespacedName' => 'Test',
                'namespace' => '',
            ],
            'php5' => false,
            'phpunit5' => false,
            'mockery' => false,
            'covers' => false,
        ], $this->templateConfiguration->toArray());
    }
}
