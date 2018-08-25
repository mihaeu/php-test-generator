<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use Mihaeu\TestGenerator\Output\OutputProcessor;
use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPUnit\Framework\MockObject\MockObject as Mock;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\TestGenerator
 */
class TestGeneratorTest extends TestCase
{
    /** @var TestGenerator */
    private $testGenerator;

    /** @var Parser | Mock */
    private $parser;

    /** @var NameResolver | Mock */
    private $nameResolver;

    /** @var ClassAnalyser | Mock */
    private $classAnalyser;

    /** @var NodeTraverser | Mock */
    private $nodeTraverser;

    /** @var TwigRenderer | Mock */
    private $twigRenderer;

    /** @var OutputProcessor | Mock */
    private $outputProcessor;

    protected function setUp() : void
    {
        $this->parser = $this->createMock(Parser::class);
        $this->classAnalyser = $this->createMock(ClassAnalyser::class);
        $this->nodeTraverser = $this->createMock(NodeTraverser::class);
        $this->twigRenderer = $this->createMock(TwigRenderer::class);
        $this->outputProcessor = $this->createMock(OutputProcessor::class);
        $this->testGenerator = new TestGenerator(
            $this->parser,
            $this->classAnalyser,
            $this->nodeTraverser,
            $this->twigRenderer,
            $this->outputProcessor
        );
    }

    public function testPrintsEmptyTemplateIfFileDoesNotHaveAConstructor() : void
    {
        $this->parser->method('parse')->willReturn([]);
        $this->classAnalyser->method('getClass')->willReturn(null);
        $file = $this->createMock(PhpFile::class);
        $file->method('content')->willReturn('');
        $this->twigRenderer->method('render')->willReturn(new Clazz('', '', ''));
        assertEquals('', $this->testGenerator->run($file));
    }

    public function testReturnsEmptyStringForFileWithoutClass() : void
    {
        $emptyFile = $this->createMock(PhpFile::class);
        $emptyFile->method('content')->willReturn('');

        $this->parser->method('parse')->willReturn([]);
        $this->classAnalyser->method('getClass')->willReturn(null);

        assertEmpty($this->testGenerator->run($emptyFile));
    }

    public function testRendersClassAndDependencies() : void
    {
        $file = $this->createMock(PhpFile::class);
        $file->method('content')->willReturn('');

        $this->parser->method('parse')->willReturn([]);
        $this->classAnalyser->method('getClass')->willReturn(new Clazz('', '', ''));

        $this->twigRenderer->expects($this->once())->method('render');
        $this->testGenerator->run($file);
    }
}
