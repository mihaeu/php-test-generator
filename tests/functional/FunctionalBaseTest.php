<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit_Framework_TestCase as TestCase;

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
    public function testGenerateSimplePhpUnitTestCase(string $source, string $expected, string $arguments, string $message) : void
    {
        $cmd = self::TEST_GENERATOR_BINARY . ' ' . $arguments . ' ' . $this->generateTestFile($source);
        $actual = shell_exec($cmd);
        assertEquals($expected, $actual, $message);
    }

    public function provideFixtures() : array
    {
        $dir = __DIR__;
        $fixtures = array_filter(
            scandir($dir . '/fixtures', SCANDIR_SORT_ASCENDING),
            function (string $dirname) use ($dir) {
                return is_dir($dir . '/fixtures/' . $dirname)
                    && strpos($dirname, '.') !== 0;
            }
        );

        $testArguments = [];
        foreach ($fixtures as $fixtureDir) {
            $fixtureDir = $dir . '/fixtures/' . $fixtureDir;
            $arguments = fileExists($fixtureDir . '/arguments.txt')
                ? str_replace(["\n", "\r"], ' ', file_get_contents($fixtureDir . '/arguments.txt'))
                : '';
            $testArguments[] = [
                file_get_contents($fixtureDir . '/source.php'),
                file_get_contents($fixtureDir . '/expected.php'),
                $arguments,
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
