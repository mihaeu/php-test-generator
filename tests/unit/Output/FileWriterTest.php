<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

use Mihaeu\TestGenerator\Output\Exception\InvalidFileException;
use Mihaeu\TestGenerator\Output\Exception\SubjectNotInSrcBaseException;
use Mihaeu\TestGenerator\Output\Exception\UnableToWriteTestFileException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mihaeu\TestGenerator\Output\FileWriter
 * @covers \Mihaeu\TestGenerator\Output\Exception\InvalidFileException
 * @covers \Mihaeu\TestGenerator\Output\Exception\UnableToWriteTestFileException
 * @covers \Mihaeu\TestGenerator\Output\Exception\SubjectNotInSrcBaseException
 */
class FileWriterTest extends TestCase
{
    private const TEST_BASE = __DIR__ . '/../../resources/simple-example-project/tests/unit/Deep';
    private const SRC_BASE = __DIR__ . '/../../resources/simple-example-project/src/Deep';
    private const SUBJECT_FILE = self::SRC_BASE . '/X.php';
    private const TEST_FILE = self::TEST_BASE . '/XTest.php';

    protected function setUp(): void
    {
        @unlink(self::TEST_FILE);
        @rmdir(self::TEST_BASE);
    }

    protected function tearDown(): void
    {
        $this->setUp();
    }

    public function testWritesOutputToFile(): void
    {
        $subjectFile = new \SplFileInfo(self::SUBJECT_FILE);
        $srcBase = new \SplFileInfo(self::SRC_BASE);
        $testBase = new \SplFileInfo(self::TEST_BASE);

        $fileWriter = new FileWriter($subjectFile, $srcBase, $testBase);
        $fileWriter->write('Test Output');
        assertSame('Test Output', file_get_contents(self::TEST_FILE));
    }

    public function testThrowsExceptionIfTestFileIsNotInSrcBase(): void
    {
        $subjectFile = new \SplFileInfo(self::SUBJECT_FILE);
        $srcBase = new \SplFileInfo(sys_get_temp_dir());
        $testBase = new \SplFileInfo(self::TEST_BASE);

        $this->expectException(SubjectNotInSrcBaseException::class);
        (new FileWriter($subjectFile, $srcBase, $testBase))->write('');
    }

    public function testThrowsExceptionIfDirectoryOfUnitTestIsNotWritable(): void
    {
        $subjectFile = new \SplFileInfo(self::SUBJECT_FILE);
        $srcBase = new \SplFileInfo(self::SRC_BASE);
        $testBase = new \SplFileInfo('/');

        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessageRegExp('/is not writable/');
        (new FileWriter($subjectFile, $srcBase, $testBase))->write('');
    }

    public function testThrowsExceptionIfUnitFileCannotBeWritten(): void
    {
        $subjectFile = new \SplFileInfo(self::SUBJECT_FILE);
        $srcBase = new \SplFileInfo(self::SRC_BASE);
        $testBase = new \SplFileInfo(self::TEST_BASE);

        @mkdir(self::TEST_BASE);
        @touch(self::TEST_FILE);
        @chmod(self::TEST_FILE, 0000);
        $this->expectException(UnableToWriteTestFileException::class);
        (new FileWriter($subjectFile, $srcBase, $testBase))->write('');
    }
}
