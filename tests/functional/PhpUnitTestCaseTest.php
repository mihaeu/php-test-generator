<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PHPUnit\Framework\TestCase;

class PhpUnitTestCaseTest extends TestCase
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

    public function testGenerateSimplePhpUnitTestCase() : void
    {
        $sourceFile = <<<'EOT'
<?php
class A {
    function __construct(ClassA $classA) {}
}
EOT;

        $expected = <<<'EOT'
<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var ClassA | PHPUnit_Framework_MockObject_MockObject */
    private $classA;

    protected function setUp()
    {
        $this->classA = $this->createMock(ClassA::class);
        $this->a = new A(
            $this->classA
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}

EOT;

        $actual = shell_exec(self::TEST_GENERATOR_BINARY . ' ' .$this->generateTestFile($sourceFile));
        assertEquals($expected, $actual);
    }

    private function generateTestFile(string $content) : string
    {
        $this->currentTestFileFilename = '/tmp/testfilefortestgenerator';
        file_put_contents($this->currentTestFileFilename, $content);
        return $this->currentTestFileFilename;
    }
}
