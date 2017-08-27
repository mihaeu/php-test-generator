<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;
use Twig\Template;

class TemplateTest extends TestCase
{
    /** @var TwigRenderer */
    private $twigRenderer;

    /** @var Template */
    private $template;

    protected function setUp()
    {
        $this->twigRenderer = new TwigRenderer(
            (new DependencyContainer())->twigEnvironment(),
            false,
            false
        );
    }

    public function testRendersTemplate()
    {
        $expected = <<<'EOT'
<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class TestTest extends TestCase
{
    /** @var Test */
    private $test;

    /** @var Customer | PHPUnit_Framework_MockObject_MockObject */
    private $customer;

    /** @var string */
    private $name;

    protected function setUp()
    {
        $this->customer = $this->createMock(Customer::class);
        $this->name = 'test';
        $this->test = new Test(
            $this->customer,
            $this->name
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}

EOT;
        $actual = $this->twigRenderer->render('Test', [
            ['name' => 'customer', 'type' => 'Customer'],
            ['name' => 'name', 'type' => 'string', 'value' => '\'test\'', 'isScalar' => true],
        ]);
        assertEquals($expected, $actual);
    }
}
