<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

use Mihaeu\TestGenerator\Output\Exception\InvalidFileException;
use PHPUnit\Framework\TestCase;

/**
 * @covers \Mihaeu\TestGenerator\Output\OutputProcessorFactory
 * @covers \Mihaeu\TestGenerator\Output\Exception\InvalidFileException
 */
class OutputProcessorFactoryTest extends TestCase
{
    public function testCreatesFileWriteIfBothSrcAndTestBaseAreSet(): void
    {
        assertInstanceOf(FileWriter::class, OutputProcessorFactory::create(
            __FILE__,
            __DIR__,
            ''
        ));
    }

    public function testThrowsExceptionIfSubjectFileIsNotReadable(): void
    {
        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessageRegExp('/is not readable/');
        OutputProcessorFactory::create(
            '/sfdsdf',
            __DIR__,
            ''
        );
    }

    public function testThrowsExceptionIfSrcBaseDoesNotExist(): void
    {
        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessageRegExp('/does not exist/');
        OutputProcessorFactory::create(
            __FILE__,
            '/sdfsdfs',
            ''
        );
    }

    public function testThrowsExceptionIfSrcBaseIsNotADirectory(): void
    {
        $this->expectException(InvalidFileException::class);
        $this->expectExceptionMessageRegExp('/does not exist/');
        OutputProcessorFactory::create(
            __FILE__,
            __FILE__,
            ''
        );
    }

    public function testCreatesStdoutWriterByDefault(): void
    {
        assertInstanceOf(StdoutWriter::class, OutputProcessorFactory::create(
            __FILE__,
            null,
            null
        ));
    }
}
