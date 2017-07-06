<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\NodeTraverser;
use PhpParser\NodeVisitor\NameResolver;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
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

    public function testPrintsEmptyTemplateIfFileDoesNotHaveAConstructor()
    {
        $this->parser->method('parse')->willReturn([]);

        $file = $this->createMock(PhpFile::class);
        $file->method('content')->willReturn('<?php class X {}');

        $expected = <<<'EOT'
<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    protected function setUp()
    {
        $this->a = new A();
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}

EOT;
        assertEquals($expected, $this->testGenerator->run($file));
    }
}
