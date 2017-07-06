<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\NodeVisitorAbstract;

class ClassAnalyser extends NodeVisitorAbstract
{
    const CONSTRUCTOR_METHOD_NAME = '__construct';

    /** @var array */
    private $parameters = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof ClassMethod && $node->name === self::CONSTRUCTOR_METHOD_NAME) {
            $this->parameters = array_reduce($node->getParams(), function (array $parameters, Param $parameter) {
                $parameters[$parameter->name] = $parameter->type->toString();
                return $parameters;
            }, $this->parameters);
        }
    }

    public function getParameters() : array
    {
        return $this->parameters;
    }
}
