<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;
use PHPUnit_Framework_MockObject_MockObject;
use Twig\Template;
use Twig_Environment;

/**
 * @covers Mihaeu\TestGenerator\TwigRenderer
 */
class TwigRendererTest extends TestCase
{
    /** @var TwigRenderer */
    private $twigRenderer;

    /** @var Twig_Environment | PHPUnit_Framework_MockObject_MockObject */
    private $twig;

    /** @var Template | PHPUnit_Framework_MockObject_MockObject */
    private $template;

    protected function setUp()
    {
        $this->template = $this->createMock(Template::class);
        $this->twig = $this->createMock(Twig_Environment::class);
        $this->twig->expects($this->once())->method('load')->willReturn($this->template);
        $this->twigRenderer = new TwigRenderer($this->twig, new TemplateConfiguration());
    }

    public function testRendersClassnameAndParameters()
    {
        $this->template->expects($this->once())->method('render')->willReturn('test');
        assertEquals('test', $this->twigRenderer->render('Test', [123]));
    }
}