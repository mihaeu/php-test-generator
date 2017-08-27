<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PhpParser\Node\Stmt\Class_;

class Clazz
{
    /** @var string */
    private $class;

    /** @var string */
    private $namespacedName;

    /** @var string */
    private $namespace;

    public function __construct(string $class, string $namespacedName, string $namespace)
    {
        $this->class = $class;
        $this->namespacedName = $namespacedName;
        $this->namespace = $namespace;
    }

    public static function fromClassNode(Class_ $classNode)
    {
        $namespaceParts = $classNode->namespacedName->parts;
        $namespace = count($namespaceParts)
            ? implode('\\', array_slice($namespaceParts, 0, -1))
            : '';
        return new Clazz(
            $classNode->name,
            implode('\\', $namespaceParts),
            $namespace
        );
    }

    public function toArray() : array
    {
        return [
            'class' => $this->class,
            'namespacedName' => $this->namespacedName,
            'namespace' => $this->namespace,
        ];
    }
}
