<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var mixed | PHPUnit_Framework_MockObject_MockObject */
    private $x;

    /** @var bool | PHPUnit_Framework_MockObject_MockObject */
    private $bool;

    /** @var int | PHPUnit_Framework_MockObject_MockObject */
    private $int;

    /** @var string | PHPUnit_Framework_MockObject_MockObject */
    private $string;

    /** @var mixed | PHPUnit_Framework_MockObject_MockObject */
    private $otherString;

    /** @var mixed | PHPUnit_Framework_MockObject_MockObject */
    private $otherInt;

    /** @var mixed | PHPUnit_Framework_MockObject_MockObject */
    private $otherFloat;

    protected function setUp()
    {
        $this->x = null;
        $this->bool = false;
        $this->int = 0;
        $this->string = '';
        $this->otherString = 'abc';
        $this->otherInt = 999;
        $this->otherFloat = 3.1415;
        $this->a = new A(
            $this->x,
            $this->bool,
            $this->int,
            $this->string,
            $this->otherString,
            $this->otherInt,
            $this->otherFloat
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
