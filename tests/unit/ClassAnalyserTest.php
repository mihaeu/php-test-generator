<?php declare(strict_types=1);

use Mihaeu\TestGenerator\ClassAnalyser;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\ClassAnalyser
 */
class ClassAnalyserTest extends TestCase
{
    /** @var ClassAnalyser */
    private $classAnalyser;

    public function setUp() : void
    {
        $this->classAnalyser = new ClassAnalyser();
    }

    public function testOnlyRegistersOnConstructors() : void
    {
        $classNode = $this->createMock(Class_::class);
        $this->classAnalyser->enterNode($classNode);
        assertEmpty($this->classAnalyser->getParameters());
    }

    public function testFindsParametersInConstructors() : void
    {
        $methodNode = $this->createMock(ClassMethod::class);
        $methodNode->name = '__construct';

        $param = $this->createMock(Param::class);
        $param->name = 'Example';
        $name = $this->createMock(PhpParser\Node\Name::class);
        $name->method('toString')->willReturn('A');
        $param->type = $name;
        $methodNode->method('getParams')->willReturn([$param]);
        $this->classAnalyser->enterNode($methodNode);
        assertEquals(['Example' => 'A'], $this->classAnalyser->getParameters());
    }

    public function testFindsClass() : void
    {
        $classNode = $this->createMock(Class_::class);
        $classNode->name = 'A';

        $this->classAnalyser->enterNode($classNode);
        assertEquals('A', $this->classAnalyser->getClass());
    }
}
