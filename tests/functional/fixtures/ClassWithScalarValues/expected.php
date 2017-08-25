<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var mixed */
    private $x;

    /** @var bool */
    private $bool;

    /** @var int */
    private $int;

    /** @var string */
    private $string;

    /** @var string */
    private $otherString;

    /** @var int */
    private $otherInt;

    /** @var float */
    private $otherFloat;

    /** @var array */
    private $xs;

    /** @var array */
    private $array;

    /** @var bool */
    private $caseInsensitive;

    /** @var mixed */
    private $fixed;

    protected function setUp()
    {
        $this->x = null;
        $this->bool = false;
        $this->int = 0;
        $this->string = '';
        $this->otherString = 'abc';
        $this->otherInt = 999;
        $this->otherFloat = 3.1415;
        $this->xs = [];
        $this->array = [];
        $this->caseInsensitive = true;
        $this->fixed = PHP_EOL;
        $this->a = new A(
            $this->x,
            $this->bool,
            $this->int,
            $this->string,
            $this->otherString,
            $this->otherInt,
            $this->otherFloat,
            $this->xs,
            $this->array,
            $this->caseInsensitive,
            $this->fixed
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
