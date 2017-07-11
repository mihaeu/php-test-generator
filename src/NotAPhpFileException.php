<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

class NotAPhpFileException extends \Exception
{
    public function __construct(\SplFileInfo $file)
    {
        parent::__construct('Argument "' . $file->getPathname() . '" is not a PHP file.');
    }
}
