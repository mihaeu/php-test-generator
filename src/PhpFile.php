<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class PhpFile
{
    /** @var \SplFileInfo */
    private $file;

    public function __construct(\SplFileInfo $file)
    {
        $this->file = $file;
    }

    public function content() : string
    {
        return file_get_contents($this->file->getRealPath());
    }
}
