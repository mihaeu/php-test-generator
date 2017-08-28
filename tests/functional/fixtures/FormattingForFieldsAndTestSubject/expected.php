<?php declare(strict_types = 1);

namespace First\Second;

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $aUnderTest;

    /** @var ClassA | PHPUnit_Framework_MockObject_MockObject */
    private $mockArg;

    protected function setUp()
    {
        $this->mockArg = $this->createMock(ClassA::class);
        $this->aUnderTest = new A(
            $this->mockArg
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
