<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\PhpFile
 */
class PhpFileTest extends TestCase
{
    public function testReturnsEmptyStringForEmptyFile() : void
    {
        $emptyFilename = '/tmp/test-generator-empty-file';
        touch($emptyFilename);
        assertEmpty((new PhpFile(new \SplFileInfo($emptyFilename)))->content());
        unlink($emptyFilename);
    }

    public function testReturnsFileContents() : void
    {
        $emptyFilename = '/tmp/test-generator-regular-file';
        file_put_contents($emptyFilename, 'testdata');
        assertEquals('testdata', (new PhpFile(new \SplFileInfo($emptyFilename)))->content());
        unlink($emptyFilename);
    }
}
