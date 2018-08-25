<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator\Output;

use PHPUnit\Framework\TestCase;

/**
 * @covers \Mihaeu\TestGenerator\Output\StdoutWriter
 */
class StdoutWriterTest extends TestCase
{
    public function testWrite(): void
    {
        ob_start();
        (new StdoutWriter())->write('Test');
        assertSame('Test', ob_get_clean());
    }
}
