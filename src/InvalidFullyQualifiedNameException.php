<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class InvalidFullyQualifiedNameException extends \InvalidArgumentException
{
    public function __construct(string $fqn)
    {
        parent::__construct("'$fqn' is not a valid fully qualified name.");
    }
}
