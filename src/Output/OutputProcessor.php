<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

interface OutputProcessor
{
    public function write(string $output): void;
}
