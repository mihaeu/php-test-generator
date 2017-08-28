<?php declare(strict_types = 1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\Clazz
 * @covers Mihaeu\TestGenerator\InvalidFullyQualifiedNameException
 */
class ClazzTest extends TestCase
{
    /** @var Clazz */
    private $clazz;

    /** @var string */
    private $name;

    /** @var string */
    private $namespacedName;

    /** @var string */
    private $namespace;

    protected function setUp()
    {
        $this->name = 'Test';
        $this->namespacedName = 'Namespace\Test';
        $this->namespace = 'Namespace';
        $this->clazz = new Clazz(
            $this->name,
            $this->namespacedName,
            $this->namespace
        );
    }

    public function testConvertsToArray() : void
    {
        assertEquals([
            'class' => 'Test',
            'namespacedName' => 'Namespace\Test',
            'namespace' => 'Namespace',
        ], $this->clazz->toArray());
    }

    public function testGenerateFromClassNode() : void
    {
        $classNode = $this->createMock(Class_::class);
        $classNode->name = 'Test';
        $name = $this->createMock(Name::class);
        $name->parts = ['Namespace', 'Test'];
        $classNode->namespacedName = $name;
        assertEquals([
            'class' => 'Test',
            'namespacedName' => 'Namespace\Test',
            'namespace' => 'Namespace',
        ], Clazz::fromClassNode($classNode)->toArray());
    }

    public function testGeneratesFromStringWithoutNamespace() : void
    {
        assertEquals(
            new Clazz('Test', 'Test', ''),
            Clazz::fromFullyQualifiedNameString('Test')
        );
    }


    public function testGeneratesFromStringWithNamespace() : void
    {
        assertEquals(
            new Clazz('Test', 'Vendor\Example\Test', 'Vendor\Example'),
            Clazz::fromFullyQualifiedNameString('Vendor\Example\Test')
        );
    }

    public function testRejectsInvalidPhpIdentifier() : void
    {
        $this->setExpectedException(InvalidFullyQualifiedNameException::class);
        Clazz::fromFullyQualifiedNameString('.');
    }

    public function testHasClazz() : void
    {
        assertEquals('Test', (new Clazz('Test', 'Test', ''))->clazz());
    }
}
