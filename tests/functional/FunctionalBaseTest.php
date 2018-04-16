<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

class FunctionalBaseTest extends TestCase
{
    const TEST_GENERATOR = __DIR__ . '/../../bin/test-generator';
    const TEST_GENERATOR_BINARY = PHP_BINARY . ' ' . self::TEST_GENERATOR;
    private const FIXTURES_DIR = __DIR__ . '/fixtures';

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
        $testArguments = [];
        foreach ($this->findFixtures() as $fixtureDir) {
            $fixtureDir = self::FIXTURES_DIR . DIRECTORY_SEPARATOR . $fixtureDir;
            $arguments = file_exists($fixtureDir . '/arguments.txt')
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
                preg_replace('/([A-Z])/', ' $1', $camelCaseText)
            )
        );
    }

    private function findFixtures(): array
    {
        return array_filter(
            scandir(self::FIXTURES_DIR, SCANDIR_SORT_ASCENDING),
            function (string $dirname) {
                return is_dir(self::FIXTURES_DIR . DIRECTORY_SEPARATOR . $dirname)
                    && strpos($dirname, '.') !== 0;
            }
        );
    }
}
