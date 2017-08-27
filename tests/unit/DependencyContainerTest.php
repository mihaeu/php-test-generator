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

    protected function setUp()
    {
        $this->dependencyContainer = new DependencyContainer($this->createMock(Response::class));
    }

    public function testGeneratesDependencies() : void
    {
        assertInstanceOf(NodeTraverser::class, $this->dependencyContainer->nodeTraverser());
        assertInstanceOf(Twig_Environment::class, $this->dependencyContainer->twigEnvironment());
        assertInstanceOf(TwigRenderer::class, $this->dependencyContainer->twigRenderer());
        assertInstanceOf(Parser::class, $this->dependencyContainer->parser());
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
