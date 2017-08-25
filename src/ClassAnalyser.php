<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node;
use PhpParser\Node\Expr;
use PhpParser\Node\Param;
use PhpParser\Node\Scalar;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class ClassAnalyser extends NodeVisitorAbstract
{
    private const CONSTRUCTOR_METHOD_NAME = '__construct';
    private const TYPE_DEFAULT_STRING = '\'\'';
    private const TYPE_DEFAULT_FLOAT = '0.0';
    private const TYPE_DEFAULT_INT = '0';
    private const TYPE_DEFAULT_BOOL = 'false';

    /** @var array */
    private $parameters = [];

    /** @var string */
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
            $this->class = $node->name;
        }
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }

    public function getClass() : ?string
    {
        return $this->class;
    }

    private function generateDefault(Param $parameter) : ?string
    {
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

        return null;
    }

    private function generateType(Param $parameter) : ?string
    {
        if (is_string($parameter->type)) {
            return $parameter->type;
        }
        return $parameter->type ? $parameter->type->toString() : null;
    }

    private function defaultToString(?Expr $default) : string
    {
        if (is_bool($default->value)) {
            return $default->value ? 'true' : 'false';
        }

        if (is_string($default->value)) {
            return "'" . $default->value . "'";
        }

        return (string) $default->value;
    }
}
