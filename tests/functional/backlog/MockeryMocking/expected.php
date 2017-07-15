<?php declare(strict_types = 1);

use Mockery;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var ClassA | MockInterface */
    private $classA;

    protected function setUp()
    {
        $this->classA = Mockery::mock(ClassA::class);
        $this->a = new A(
            $this->classA
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
