<?php

declare(strict_types=1);

namespace Mihaeu\TestGenerator;

use PDepend\Util\Type;
use PHPUnit\Framework\TestCase;

/**
 * @covers Mihaeu\TestGenerator\Dependency
 */
class DependencyTest extends TestCase
{
    public function testHasName() : void
    {
        assertEquals('test', (new Dependency('test'))->name());
    }

    public function testHasType() : void
    {
        assertEquals('int', (new Dependency('test', 'int'))->type());
    }

    public function testHasValue() : void
    {
        assertEquals('3.1415', (new Dependency('test', null, '3.1415'))->value());
    }

    /**
     * @dataProvider typeProvider
     */
    public function testDetectsIfDependencyTypeIsScalar(string $type, bool $expected) : void
    {
        assertEquals($expected, (new Dependency('test', $type))->isScalar());
    }

    public function typeProvider()
    {
        return [
            ['int', true],
            ['float', true],
            ['string', true],
            ['array', true],
            ['bool', true],
            ['ArrayObject', false],
            ['', false],
        ];
    }
}
