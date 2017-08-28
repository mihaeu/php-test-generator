<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class TemplateConfiguration
{
    /** @var Clazz */
    private $baseClass;

    /** @var bool */
    private $php5;

    /** @var bool */
    private $phpunit5;

    /** @var bool */
    private $mockery;

    /** @var bool */
    private $covers;

    public function __construct(
        Clazz $baseClass,
        $php5 = false,
        $phpunit5 = false,
        $mockery = false,
        $covers = false
    ) {
        $this->baseClass = $baseClass;
        $this->php5 = $php5;
        $this->phpunit5 = $phpunit5;
        $this->mockery = $mockery;
        $this->covers = $covers;
    }

    public function toArray() : array
    {
        return [
            'baseClass' => $this->baseClass->toArray(),
            'php5' => $this->php5,
            'phpunit5' => $this->phpunit5,
            'mockery' => $this->mockery,
            'covers' => $this->covers,
        ];
    }
}
