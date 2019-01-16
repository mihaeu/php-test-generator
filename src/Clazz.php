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

    public function clazz(): string
    {
        return $this->class;
    }

    public static function fromClassNode(Class_ $classNode) : Clazz
    {
        $namespaceParts = $classNode->namespacedName->parts;
        $namespace = count($namespaceParts)
            ? implode('\\', array_slice($namespaceParts, 0, -1))
            : '';
        return new Clazz(
            (string) $classNode->name,
            implode('\\', $namespaceParts),
            $namespace
        );
    }

    public static function fromFullyQualifiedNameString(string $fqn) : Clazz
    {
        self::assertNameIsValidPhpIdentifier($fqn);

        $parts = explode('\\', $fqn);
        $namespace = implode('\\', array_slice($parts, 0, -1));
        return new Clazz($parts[count($parts) - 1], $fqn, $namespace);
    }

    private static function assertNameIsValidPhpIdentifier(string $fqn): void
    {
        if (!preg_match('/^[a-zA-Z_\x7f-\xff][\\a-zA-Z0-9_\x7f-\xff]*$/', $fqn)) {
            throw new InvalidFullyQualifiedNameException($fqn);
        }
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
