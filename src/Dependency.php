<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class Dependency
{
    const DEFAULT_TYPE = 'null';

    /** @var string */
    private $name;

    /** @var string */
    private $type;

    /** @var string */
    private $value;

    public function __construct(string $name, string $type = null, string $value = null)
    {
        $this->name = $name;
        $this->type = $type;
        $this->value = $value;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function type(): ?string
    {
        return $this->type;
    }

    public function value(): ?string
    {
        return $this->value;
    }

    public function isScalar() : bool
    {
        return $this->type === 'bool'
            || $this->type === 'int'
            || $this->type === 'float'
            || $this->type === 'array'
            || $this->type === 'string'
            || $this->type === null;
    }
}
