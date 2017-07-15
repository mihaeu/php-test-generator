<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;
use ClassA as B;
use PHPUnit_Framework_MockObject_MockObject as Mock;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var B | Mock */
    private $b;

    protected function setUp()
    {
        $this->classA = $this->createMock(B::class);
        $this->a = new A(
            $this->b
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
