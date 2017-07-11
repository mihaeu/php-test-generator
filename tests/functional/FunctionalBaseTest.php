<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

class FunctionalBaseTest extends TestCase
{
    const TEST_GENERATOR_BINARY = PHP_BINARY . ' ' . __DIR__ . '/../../bin/test-generator';

    /** @var string */
    private $currentTestFileFilename;

    protected function tearDown() : void
    {
        if (file_exists($this->currentTestFileFilename)) {
            unlink($this->currentTestFileFilename);
        }
    }

    /**
     * @dataProvider provideFixtures
     */
    public function testGenerateSimplePhpUnitTestCase(string $source, string $expected, string $message) : void
    {
        $actual = shell_exec(self::TEST_GENERATOR_BINARY . ' ' .$this->generateTestFile($source));
        assertEquals($expected, $actual, $message);
    }

    public function provideFixtures() : array
    {
        $dir = __DIR__;
        $fixtures = array_filter(scandir($dir . '/fixtures'), function (string $dirname) use ($dir) {
            return is_dir($dir . '/fixtures/' . $dirname)
                && strpos($dirname, '.') !== 0;
        });

        $testArguments = [];
        foreach ($fixtures as $fixtureDir) {
            $testArguments[] = [
                file_get_contents($dir . '/fixtures/' . $fixtureDir . '/source.php'),
                file_get_contents($dir . '/fixtures/' . $fixtureDir . '/expected.php'),
                $this->camelCaseToReadable($fixtureDir),
            ];
        }
        return $testArguments;
    }

    private function generateTestFile(string $content) : string
    {
        $this->currentTestFileFilename = '/tmp/testfilefortestgenerator.php';
        file_put_contents($this->currentTestFileFilename, $content);
        return $this->currentTestFileFilename;
    }

    private function camelCaseToReadable(string $camelCaseText) : string
    {
        return strtolower(
            trim(
                preg_replace('/[A-Z]/', ' $1', $camelCaseText)
            )
        );
    }
}
