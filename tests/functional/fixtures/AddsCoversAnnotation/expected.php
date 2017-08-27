<?php declare(strict_types = 1);

namespace Testspace;

use PHPUnit\Framework\TestCase;

/**
 * @covers Testspace\A
 */
class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var Other\ClassA | PHPUnit_Framework_MockObject_MockObject */
    private $classA;

    protected function setUp()
    {
        $this->classA = $this->createMock(Other\ClassA::class);
        $this->a = new A(
            $this->classA
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
