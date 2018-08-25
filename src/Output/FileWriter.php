<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

use Mihaeu\TestGenerator\Output\Exception\InvalidFileException;
use Mihaeu\TestGenerator\Output\Exception\SubjectNotInSrcBaseException;
use Mihaeu\TestGenerator\Output\Exception\UnableToWriteTestFileException;

class FileWriter implements OutputProcessor
{
    private const PHP_EXTENSION = '.php';
    private const TEST_FILE_EXTENSION = 'Test';

    /** @var \SplFileInfo */
    private $subjectFile;

    /** @var \SplFileInfo */
    private $srcBase;

    /** @var \SplFileInfo */
    private $testBase;

    public function __construct(
        \SplFileInfo $subjectFile,
        \SplFileInfo $srcBase,
        \SplFileInfo $testBase
    ) {
        $this->assertSubjectIsInSrcBase($subjectFile, $srcBase);

        $this->subjectFile = $subjectFile;
        $this->srcBase = $srcBase;
        $this->testBase = $testBase;
    }

    /**
     * @param string $output
     * @throws InvalidFileException
     * @throws UnableToWriteTestFileException
     */
    public function write(string $output): void
    {
        $bytesWritten = @file_put_contents($this->pathToTestFile(), $output);
        if ($bytesWritten === false) {
            throw new UnableToWriteTestFileException;
        }
    }

    /**
     * @throws InvalidFileException
     */
    private function pathToTestFile(): string
    {
        $projectPath = str_replace(
            $this->srcBase->getRealPath(),
            '',
            $this->subjectFile->getPathInfo()->getRealPath()
        );
        $testDirectory = $this->testBase->getPathname() . DIRECTORY_SEPARATOR . $projectPath;
        if ((!mkdir($testDirectory, 0777, true)
            && !is_dir($testDirectory))
            || !is_writable(dirname($testDirectory))
        ) {
            throw InvalidFileException::becauseFileIsNotWritable($testDirectory);
        }

        return realpath($testDirectory)
            . DIRECTORY_SEPARATOR
            . $this->subjectFile->getBasename(self::PHP_EXTENSION)
            . self::TEST_FILE_EXTENSION
            . self::PHP_EXTENSION;
    }

    private function assertSubjectIsInSrcBase(\SplFileInfo $subjectFile, \SplFileInfo $srcBase): void
    {
        if (strpos($subjectFile->getRealPath(), $srcBase->getRealPath()) !== 0) {
            throw new SubjectNotInSrcBaseException($subjectFile, $srcBase);
        }
    }
}
