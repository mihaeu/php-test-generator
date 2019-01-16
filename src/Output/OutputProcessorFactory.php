<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

use Mihaeu\TestGenerator\Output\Exception\InvalidFileException;

class OutputProcessorFactory
{
    /**
     * @param string $subjectFile
     * @param string|null $srcBase
     * @param string|null $testBase
     * @return OutputProcessor
     * @throws InvalidFileException
     */
    public static function create(
        string $subjectFile,
        string $srcBase = null,
        string $testBase = null
    ): OutputProcessor {
        if (isset($srcBase, $testBase)) {
            return self::createFileWriter($subjectFile, $srcBase, $testBase);
        }
        return self::createStdoutWriter();
    }

    /**
     * @param string $subjectFile
     * @param string $srcBase
     * @param string $testBase
     * @return FileWriter
     * @throws InvalidFileException
     */
    private static function createFileWriter(
        string $subjectFile,
        string $srcBase,
        string $testBase
    ): FileWriter {
        if (!is_readable($subjectFile)) {
            throw InvalidFileException::becauseFileIsNotReadable($subjectFile);
        }

        if (!is_dir($srcBase)) {
            throw InvalidFileException::becauseFileDoesNotExist($srcBase);
        }

        return new FileWriter(
            new \SplFileInfo($subjectFile),
            new \SplFileInfo($srcBase),
            new \SplFileInfo($testBase)
        );
    }

    private static function createStdoutWriter(): StdoutWriter
    {
        return new StdoutWriter();
    }
}
