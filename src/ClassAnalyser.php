<?php declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node;
use PhpParser\Node\Param;
use PhpParser\NodeVisitorAbstract;

class ClassAnalyser extends NodeVisitorAbstract
{
    /** @var array */
    private $parameters = [];

    public function enterNode(Node $node)
    {
        if ($node instanceof Node\Stmt\ClassMethod
            && $node->name === '__construct') {
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
