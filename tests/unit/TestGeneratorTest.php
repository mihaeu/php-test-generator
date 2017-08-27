<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPUnit_Framework_TestCase as TestCase;
use PHPUnit_Framework_MockObject_MockObject as Mock;

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

    protected function setUp() : void
    {
        $this->parser = $this->createMock(Parser::class);
        $this->classAnalyser = $this->createMock(ClassAnalyser::class);
        $this->nodeTraverser = $this->createMock(NodeTraverser::class);
        $this->twigRenderer = $this->createMock(TwigRenderer::class);
        $this->testGenerator = new TestGenerator(
            $this->parser,
            $this->classAnalyser,
            $this->nodeTraverser,
            $this->twigRenderer
        );
    }

    public function testPrintsEmptyTemplateIfFileDoesNotHaveAConstructor() : void
    {
        $this->parser->method('parse')->willReturn([]);
        $this->classAnalyser->method('getClass')->willReturn('A');
        $file = $this->createMock(PhpFile::class);
        $file->method('content')->willReturn('');
        $this->twigRenderer->method('render')->willReturn('test');
        assertEquals('test', $this->testGenerator->run($file));
    }

    public function testReturnsEmptyStringForFileWithoutClass() : void
    {
        $emptyFile = $this->createMock(PhpFile::class);
        $emptyFile->method('content')->willReturn('');

        $this->parser->method('parse')->willReturn([]);
        $this->classAnalyser->method('getClass')->willReturn(null);

        assertEmpty($this->testGenerator->run($emptyFile));
    }
}
