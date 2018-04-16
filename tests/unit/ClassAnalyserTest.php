<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Name;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar\DNumber;
use PhpParser\Node\Scalar\LNumber;
use PhpParser\Node\Scalar\String_;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\ClassAnalyser
 */
class ClassAnalyserTest extends TestCase
{
    private const REGEX_FLOAT = '/^\d+\.\d+$/';
    private const REGEX_INT = '/^\d+$/';
    private const TYPE_BOOL_TRUE = 'true';
    private const TYPE_BOOL_FALSE = 'false';

    /** @var ClassAnalyser */
    private $classAnalyser;

    protected function setUp() : void
    {
        $this->classAnalyser = new ClassAnalyser();
    }

    public function testOnlyRegistersOnConstructors() : void
    {
        $classNode = $this->createMock(Class_::class);
        $name = $this->createMock(Name::class);
        $name->name = 'Test';
        $name->parts = ['Test'];
        $classNode->name = 'Test';
        $classNode->namespacedName = $name;
        $this->classAnalyser->enterNode($classNode);
        assertEmpty($this->classAnalyser->getParameters());
    }

    public function testFindsParametersInConstructors() : void
    {
        $methodNode = $this->createMock(ClassMethod::class);
        $methodNode->name = '__construct';

        $className = $this->createMock(Name::class);
        $className->method('toString')->willReturn('A');

        $param = $this->createMock(Param::class);
        $param->name = 'example';
        $param->type = $className;

        $methodNode->method('getParams')->willReturn([$param]);
        $this->classAnalyser->enterNode($methodNode);

        assertEquals(
            ['example' => new Dependency('example', 'A')],
            $this->classAnalyser->getParameters()
    );
    }

    public function testAnalysesOnlyClassConstructors() : void
    {
        $functionNode = $this->createMock(Function_::class);
        $functionNode->name = '__construct';

        $param = $this->createMock(Param::class);
        $param->name = 'Example';
        $name = $this->createMock(Name::class);
        $name->method('toString')->willReturn('A');
        $param->type = $name;
        $functionNode->method('getParams')->willReturn([$param]);

        $this->classAnalyser->enterNode($functionNode);
        assertEmpty($this->classAnalyser->getParameters());
    }

    public function testFindsClass() : void
    {
        $classNode = $this->createMock(Class_::class);
        $classNode->name = 'b';
        $name = $this->createMock(Name::class);
        $name->parts = ['a', 'b'];
        $classNode->namespacedName = $name;

        $this->classAnalyser->enterNode($classNode);
        $expected = new Clazz('b', 'a\b', 'a');
        assertEquals($expected, $this->classAnalyser->getClass());
    }

    /**
     * @dataProvider parameterProvider
     */
    public function testGeneratesDefaults(string $message, $type, ?string $default, ?string $expected) : void
    {
        $this->classAnalyser->enterNode(
            $this->createConstructorWithOneParamenter($message, $type, $default)
        );
        $parameters = $this->classAnalyser->getParameters();
        assertEquals($expected, array_pop($parameters)->value(), $message);
    }

    public function parameterProvider() : array
    {
        return [
            [
                'message' => 'Object without default',
                'type' => 'ArrayObject',
                'default' => null,
                'expected' => '',
            ],
            [
                'message' => 'No arguments',
                'type' => null,
                'default' => null,
                'expected' => null,
            ],
            [
                'message' => 'Bool with no default',
                'type' => 'bool',
                'default' => null,
                'expected' => self::TYPE_BOOL_FALSE,
            ],
            [
                'message' => 'No type with true default',
                'type' => null,
                'default' => self::TYPE_BOOL_TRUE,
                'expected' => self::TYPE_BOOL_TRUE,
            ],
            [
                'message' => 'No type with TRUE default',
                'type' => null,
                'default' => 'TRUE',
                'expected' => self::TYPE_BOOL_TRUE,
            ],
            [
                'message' => 'No type with false default',
                'type' => null,
                'default' => self::TYPE_BOOL_FALSE,
                'expected' => self::TYPE_BOOL_FALSE,
            ],
            [
                'message' => 'Int with no default',
                'type' => 'int',
                'default' => null,
                'expected' => '0',
            ],
            [
                'message' => 'No type with int default',
                'type' => null,
                'default' => '123',
                'expected' => '123',
            ],
            [
                'message' => 'Float type with no default',
                'type' => 'float',
                'default' => null,
                'expected' => '0.0',
            ],
            [
                'message' => 'No type with float default',
                'type' => null,
                'default' => '3.1415',
                'expected' => '3.1415',
            ],
            [
                'message' => 'String with no default',
                'type' => 'string',
                'default' => null,
                'expected' => "''",
            ],
            [
                'message' => 'No type with string default',
                'type' => null,
                'default' => '"string"',
                'expected' => "'string'",
            ],
            [
                'message' => 'Array type with no default',
                'type' => 'array',
                'default' => null,
                'expected' => '[]',
            ],
            [
                'message' => 'No type with array default',
                'type' => null,
                'default' => '[]',
                'expected' => '[]',
            ],
            [
                'message' => 'No type with defined global constant',
                'type' => null,
                'default' => 'SOME_CONST',
                'expected' => 'SOME_CONST',
            ],
        ];
    }

    private function createConstructorWithOneParamenter(string $message, ?string $type, $default): ClassMethod
    {
        $param = $this->createMock(Param::class);
        $param->name = str_replace(' ', '_', $message);
        $param->type = $this->typeFromString($type);
        $param->default = $this->defaultToNode($default);

        $functionNode = $this->createMock(ClassMethod::class);
        $functionNode->name = '__construct';
        $functionNode->method('getParams')->willReturn([$param]);
        return $functionNode;
    }

    private function typeFromString(?string $typeDefinition)
    {
        if ($typeDefinition === null) {
            return null;
        }

        if (
            $typeDefinition === 'bool'
            || $typeDefinition === 'float'
            || $typeDefinition === 'double'
            || $typeDefinition === 'int'
            || $typeDefinition === 'string'
            || $typeDefinition === 'array'
        ) {
            return $typeDefinition;
        }

        $className = $this->createMock(Name::class);
        $className->method('toString')->willReturn($typeDefinition);
        return $className;
    }

    private function defaultToNode($default)
    {
        if ($default === null) {
            return null;
        }

        if (preg_match(self::REGEX_FLOAT, $default)) {
            $float = $this->createMock(DNumber::class);
            $float->value = (float) $default;
            return $float;
        }

        if (preg_match(self::REGEX_INT, $default)) {
            $int = $this->createMock(LNumber::class);
            $int->value = (int) $default;
            return $int;
        }

        if ($default === self::TYPE_BOOL_TRUE
            || $default === self::TYPE_BOOL_FALSE
            || $default === 'TRUE'
            || $default === 'FALSE'
        ) {
            $bool = $this->createMock(ConstFetch::class);
            $bool->value = stripos($default, self::TYPE_BOOL_TRUE) !== false;
            $bool->name = $this->createMock(Name::class);
            $bool->name->method('toString')->willReturn($default);
            return $bool;
        }

        if ($default === '[]') {
            return $this->createMock(Array_::class);
        }

        if (preg_match('/^[\'"]/', $default)) {
            $string = $this->createMock(String_::class);
            $string->value = trim($default, '\'""');
            return $string;
        }

        $const = $this->createMock(ConstFetch::class);
        $const->name = $this->createMock(Name::class);
        $const->name->method('toString')->willReturn($default);
        return $const;
    }
}
