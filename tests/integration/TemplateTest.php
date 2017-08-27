<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use Docopt\Response;
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
        $args = new Response([
            '--php5' => false,
            '--phpunit5' => false,
            '--mockery' => false,
            '--covers' => false,
        ]);
        $this->twigRenderer = (new DependencyContainer($args))->twigRenderer();
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
        $actual = $this->twigRenderer->render(new Clazz('Test', '', ''), [
            ['name' => 'customer', 'type' => 'Customer'],
            ['name' => 'name', 'type' => 'string', 'value' => '\'test\'', 'isScalar' => true],
        ]);
        assertEquals($expected, $actual);
    }
}
