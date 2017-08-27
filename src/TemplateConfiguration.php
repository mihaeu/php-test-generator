<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class TemplateConfiguration
{
    /** @var bool */
    private $php5;

    /** @var bool */
    private $phpunit5;

    /** @var bool */
    private $mockery;

    public function __construct($php5 = false, $phpunit5 = false, $mockery = false)
    {
        $this->php5 = $php5;
        $this->phpunit5 = $phpunit5;
        $this->mockery = $mockery;
    }

    public function toArray() : array
    {
        return [
            'php5' => $this->php5,
            'phpunit5' => $this->phpunit5,
            'mockery' => $this->mockery,
        ];
    }
}
