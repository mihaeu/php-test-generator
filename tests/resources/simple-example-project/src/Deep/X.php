<?php

declare(strict_types=1);

namespace Something\Different;

use Mihaeu\TestGenerator\Dependency;
use PhpParser\Node\Expr\Cast\Object_;

class X
{
    /** @var Object_ */
    private $object;

    /** @var Dependency */
    private $dependency;

    public function __construct(Object_ $object, Dependency $dependency)
    {
        $this->object = $object;
        $this->dependency = $dependency;
    }
}
