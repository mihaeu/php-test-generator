<?php

use Mockery;
use Mockery\MockInterface;
use PHPUnit_Framework_TestCase as TestCase;

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
