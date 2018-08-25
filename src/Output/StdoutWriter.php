<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

class StdoutWriter implements OutputProcessor
{
    public function write(string $output): void
    {
        echo $output;
    }
}
