<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use Docopt\Response;
use PhpParser\NodeTraverser;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use Twig_Environment;

/**
 * @covers Mihaeu\TestGenerator\DependencyContainer
 */
class DependencyContainerTest extends TestCase
{
    /** @var DependencyContainer */
    private $dependencyContainer;

    /** @var Response */
    private $args;

    protected function setUp()
    {
        $this->args = $this->createMock(Response::class);
        $this->dependencyContainer = new DependencyContainer($this->args);
    }

    public function testGeneratesNodeTraverser() : void
    {
        assertInstanceOf(NodeTraverser::class, $this->dependencyContainer->nodeTraverser());
    }

    public function testGeneratesTwig_Environment() : void
    {
        assertInstanceOf(Twig_Environment::class, $this->dependencyContainer->twigEnvironment());
    }

    public function testGeneratesTwigRenderer() : void
    {
        assertInstanceOf(TwigRenderer::class, $this->dependencyContainer->twigRenderer());
    }

    public function testGeneratesParser() : void
    {
        assertInstanceOf(Parser::class, $this->dependencyContainer->parser());
    }

    public function testGeneratesTemplateConfiguration() : void
    {
        assertInstanceOf(TemplateConfiguration::class, $this->dependencyContainer->templateConfiguration());
    }

    public function testGenerateBaseClassFromDefaultForPhpunit6() : void
    {
        $dependencyContainer = new DependencyContainer(new Response([
            '--php5' => false,
            '--base-class' => false,
        ]));
        assertEquals(
            new Clazz('TestCase', 'PHPUnit\\Framework\\TestCase', 'PHPUnit\\Framework'),
            $dependencyContainer->baseClass()
        );
    }

    public function testGenerateBaseClassFromDefaultForPhpunit5() : void
    {
        $dependencyContainer = new DependencyContainer(new Response([
            '--php5' => true,
            '--base-class' => false,
        ]));
        assertEquals(
            new Clazz('PHPUnit_Framework_TestCase', 'PHPUnit_Framework_TestCase', ''),
            $dependencyContainer->baseClass()
        );
    }

    public function testGenerateBaseClass() : void
    {
        $dependencyContainer = new DependencyContainer(new Response([
            '--php5' => true,
            '--base-class' => 'Vendor\\Test',
        ]));
        assertEquals(
            new Clazz('Test', 'Vendor\\Test', 'Vendor'),
            $dependencyContainer->baseClass()
        );
    }

    public function testLcfirstFilter() : void
    {
        $callable = $this->dependencyContainer->lcfirstFilter()->getCallable();
        assertEquals('test', $callable('Test'));
    }

    public function testIsNullFilter() : void
    {
        $callable = $this->dependencyContainer->isNullFilter()->getCallable();
        assertTrue($callable(null));
        assertFalse($callable(false));
        assertFalse($callable(''));
        assertFalse($callable(0));
    }
}
