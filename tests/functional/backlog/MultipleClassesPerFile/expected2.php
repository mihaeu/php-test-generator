<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class BTest extends TestCase
{
    /** @var B */
    private $a;

    /** @var ClassB | PHPUnit_Framework_MockObject_MockObject */
    private $classB;

    protected function setUp()
    {
        $this->classB = $this->createMock(ClassB::class);
        $this->b = new B(
            $this->classB
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
