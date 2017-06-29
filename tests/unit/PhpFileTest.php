<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\PhpFile
 */
class PhpFileTest extends TestCase
{
    public function testReturnsEmptyStringForEmptyFile()
    {
        $emptyFilename = '/tmp/test-generator-empty-file';
        touch($emptyFilename);
        assertEmpty((new PhpFile(new \SplFileInfo($emptyFilename)))->content());
        unlink($emptyFilename);
    }
}
