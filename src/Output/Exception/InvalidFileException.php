<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output\Exception;

class InvalidFileException extends \Exception
{
    public static function becauseFileIsNotWritable(string $path): self
    {
        return new self(sprintf('"%s" is not writable.', $path));
    }

    public static function becauseFileIsNotReadable(string $path): self
    {
        return new self(sprintf('"%s" is not readable.', $path));
    }

    public static function becauseFileDoesNotExist(string $path): self
    {
        return new self(sprintf('"%s" does not exist.', $path));
    }
}
