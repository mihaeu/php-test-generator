<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class ClassAnalyser extends NodeVisitorAbstract
{
    private const CONSTRUCTOR_METHOD_NAME = '__construct';
    private const TYPE_DEFAULT_STRING = '\'\'';
    private const TYPE_DEFAULT_FLOAT = '0.0';
    private const TYPE_DEFAULT_INT = '0';
    private const TYPE_DEFAULT_BOOL = 'false';
    private const TYPE_DEFAULT_ARRAY = '[]';

    /** @var Dependency[] */
    private $parameters = [];

    /** @var Clazz */
    private $class;

    public function enterNode(Node $node)
    {
        if ($node instanceof ClassMethod
            && $node->name === self::CONSTRUCTOR_METHOD_NAME
        ) {
            $this->parameters = array_reduce($node->getParams(), function (array $parameters, Param $parameter) {
                $parameters[$parameter->name] = new Dependency(
                    $parameter->name,
                    $this->generateType($parameter),
                    $this->generateDefault($parameter)
                );
                return $parameters;
            }, $this->parameters);
        } elseif ($node instanceof Node\Stmt\Class_) {
            $this->class = Clazz::fromClassNode($node);
        }
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }

    public function getClass() : ?Clazz
    {
        return $this->class;
    }

    private function generateDefault(Param $parameter) : ?string
    {
        if ($parameter->default instanceof Expr\Array_) {
            return self::TYPE_DEFAULT_ARRAY;
        }

        if ($parameter->default) {
            return $this->defaultToString($parameter->default);
        }

        if ($parameter->type === 'string') {
            return self::TYPE_DEFAULT_STRING;
        }

        if ($parameter->type === 'float') {
            return self::TYPE_DEFAULT_FLOAT;
        }

        if ($parameter->type === 'int') {
            return self::TYPE_DEFAULT_INT;
        }

        if ($parameter->type === 'bool') {
            return self::TYPE_DEFAULT_BOOL;
        }

        if ($parameter->type === 'array') {
            return self::TYPE_DEFAULT_ARRAY;
        }

        return null;
    }

    private function generateType(Param $parameter) : ?string
    {
        if (is_string($parameter->type)) {
            return $parameter->type;
        }

        if ($parameter->type) {
            return $parameter->type->toString();
        }

        return $this->guessTypeFromDefault($parameter);
    }

    private function defaultToString(Expr $default) : string
    {
        if ($default instanceof Expr\ConstFetch) {
            if (preg_match('/(false|true)/i', $default->name->toString())) {
                return strtolower($default->name->toString());
            }
            return $default->name->toString();
        }

        if (is_string($default->value)) {
            return "'" . $default->value . "'";
        }

        return (string) $default->value;
    }

    private function guessTypeFromDefault(Param $parameter) : ?string
    {
        if ($parameter->default instanceof Expr\Array_) {
            return 'array';
        }

        if ($parameter->default instanceof Node\Scalar\LNumber) {
            return 'int';
        }

        if ($parameter->default instanceof Node\Scalar\DNumber) {
            return 'float';
        }

        if ($parameter->default instanceof Node\Scalar\String_) {
            return 'string';
        }

        if ($parameter->default instanceof Expr\ConstFetch
            && preg_match('/(true|false)/i', $parameter->default->name->toString())
        ) {
            return 'bool';
        }

        return null;
    }
}
