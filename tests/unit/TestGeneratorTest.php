<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\TestGenerator
 */
class TestGeneratorTest extends TestCase
{
    /** @var TestGenerator */
    private $testGenerator;

    /** @var Parser */
    private $parser;

    /** @var NameResolver */
    private $nameResolver;

    /** @var ClassAnalyser */
    private $classAnalyser;

    /** @var NodeTraverser */
    private $nodeTraverser;


    public function setUp()
    {
        $this->parser = $this->createMock(Parser::class);
        $this->nameResolver = $this->createMock(NameResolver::class);
        $this->classAnalyser = $this->createMock(ClassAnalyser::class);
        $this->nodeTraverser = $this->createMock(NodeTraverser::class);
        $this->testGenerator = new TestGenerator(
            $this->parser,
            $this->nameResolver,
            $this->classAnalyser,
            $this->nodeTraverser
        );
    }

    public function testDoesNotPrintTemplateIfFileDoesNotHaveAConstructor()
    {
        $this->parser->method('parse')->willReturn([]);

        $file = $this->createMock(PhpFile::class);
        $file->method('content')->willReturn('<?php class A {}');
        assertEmpty($this->testGenerator->run($file));
    }
}
