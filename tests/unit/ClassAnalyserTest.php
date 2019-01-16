<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node\Expr;
use PhpParser\Node\Expr\Array_;
use PhpParser\Node\Expr\ConstFetch;
use PhpParser\Node\Expr\Variable;
use PhpParser\Node\Identifier;
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
        $param = new Param(
            new Variable('example'),
            null,
            new Identifier('A')
        );
        $methodNode = new ClassMethod(
            new Identifier('__construct'),
            ['params' => [$param]]
        );

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
     * @param string $message
     * @param $type
     * @param string|null $default
     * @param string|null $expected
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
            'Object without default' => [
                'message' => 'Object without default',
                'type' => 'ArrayObject',
                'default' => null,
                'expected' => '',
            ],
            'No arguments' => [
                'message' => 'No arguments',
                'type' => null,
                'default' => null,
                'expected' => null,
            ],
            'Bool with no default' => [
                'message' => 'Bool with no default',
                'type' => 'bool',
                'default' => null,
                'expected' => self::TYPE_BOOL_FALSE,
            ],
            'No type with true default' => [
                'message' => 'No type with true default',
                'type' => null,
                'default' => self::TYPE_BOOL_TRUE,
                'expected' => self::TYPE_BOOL_TRUE,
            ],
            'No type with TRUE default' => [
                'message' => 'No type with TRUE default',
                'type' => null,
                'default' => 'TRUE',
                'expected' => self::TYPE_BOOL_TRUE,
            ],
            'No type with false default' => [
                'message' => 'No type with false default',
                'type' => null,
                'default' => self::TYPE_BOOL_FALSE,
                'expected' => self::TYPE_BOOL_FALSE,
            ],
            'Int with no default' => [
                'message' => 'Int with no default',
                'type' => 'int',
                'default' => null,
                'expected' => '0',
            ],
            'No type with int default' => [
                'message' => 'No type with int default',
                'type' => null,
                'default' => '123',
                'expected' => '123',
            ],
            'Float type with no default' => [
                'message' => 'Float type with no default',
                'type' => 'float',
                'default' => null,
                'expected' => '0.0',
            ],
            'No type with float default' => [
                'message' => 'No type with float default',
                'type' => null,
                'default' => '3.1415',
                'expected' => '3.1415',
            ],
            'String with no default' => [
                'message' => 'String with no default',
                'type' => 'string',
                'default' => null,
                'expected' => "''",
            ],
            'No type with string default' => [
                'message' => 'No type with string default',
                'type' => null,
                'default' => '"string"',
                'expected' => "'string'",
            ],
            'Array type with no default' => [
                'message' => 'Array type with no default',
                'type' => 'array',
                'default' => null,
                'expected' => '[]',
            ],
            'No type with array default' => [
                'message' => 'No type with array default',
                'type' => null,
                'default' => '[]',
                'expected' => '[]',
            ],
            'No type with defined global constant' => [
                'message' => 'No type with defined global constant',
                'type' => null,
                'default' => 'SOME_CONST',
                'expected' => 'SOME_CONST',
            ],
        ];
    }

    private function createConstructorWithOneParamenter(string $message, ?string $type, $default): ClassMethod
    {
        $param = new Param(
            new Variable(str_replace(' ', '_', $message)),
            $this->defaultToNode($default),
            $this->typeFromString($type)
        );

        return new ClassMethod('__construct', ['params' => [$param]]);
    }

    private function typeFromString(?string $typeDefinition): ?Name
    {
        if ($typeDefinition === null) {
            return null;
        }

        if ($typeDefinition === 'bool'
            || $typeDefinition === 'float'
            || $typeDefinition === 'double'
            || $typeDefinition === 'int'
            || $typeDefinition === 'string'
            || $typeDefinition === 'array'
        ) {
            return new Name($typeDefinition);
        }

        return new Name($typeDefinition);
    }

    private function defaultToNode($default): ?Expr
    {
        if ($default === null) {
            return null;
        }

        if (preg_match(self::REGEX_FLOAT, $default)) {
            return new DNumber((float) $default);
        }

        if (preg_match(self::REGEX_INT, $default)) {
            return new LNumber((int) $default);
        }

        if ($default === self::TYPE_BOOL_TRUE
            || $default === self::TYPE_BOOL_FALSE
            || $default === 'TRUE'
            || $default === 'FALSE'
        ) {
//            $bool = $this->createMock(ConstFetch::class);
//            $bool->value = stripos($default, self::TYPE_BOOL_TRUE) !== false;
//            $bool->name = $this->createMock(Name::class);
//            $bool->name->method('toString')->willReturn($default);
            return new ConstFetch(new Name($default));
        }

        if ($default === '[]') {
            return new Array_();
        }

        if (preg_match('/^[\'"]/', $default)) {
            return new String_(trim($default, '\'""'));
        }

        return new ConstFetch(new Name($default));
    }
}
