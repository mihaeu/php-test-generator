<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\PhpFile
 * @covers Mihaeu\TestGenerator\NotAPhpFileException
 */
class PhpFileTest extends TestCase
{
    public function testReturnsEmptyStringForEmptyFile() : void
    {
        $emptyFilename = '/tmp/test-generator-empty-file.php';
        touch($emptyFilename);
        assertEmpty((new PhpFile(new \SplFileInfo($emptyFilename)))->content());
        unlink($emptyFilename);
    }

    public function testReturnsFileContents() : void
    {
        $regularFilename = '/tmp/test-generator-regular-file.php';
        file_put_contents($regularFilename, 'testdata');
        assertEquals('testdata', (new PhpFile(new \SplFileInfo($regularFilename)))->content());
        unlink($regularFilename);
    }

    public function testDoesNotAcceptDirectories() : void
    {
        $this->expectException(NotAPhpFileException::class);
        new PhpFile(new \SplFileInfo(sys_get_temp_dir()));
    }

    public function testDoesNotAcceptDirectoriesThatLookLikePhpFiles() : void
    {
        $this->expectException(NotAPhpFileException::class);
        new PhpFile(new \SplFileInfo(sys_get_temp_dir().'.php'));
    }

    public function testDoesNotAcceptFilesWithoutPhpExtension() : void
    {
        $this->expectException(NotAPhpFileException::class);
        new PhpFile(new \SplFileInfo('missing-php-extions.phtml'));
    }
}
