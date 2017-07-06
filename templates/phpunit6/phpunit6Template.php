<?php

namespace Mihaeu\TestGenerator;

function phpunit6Template(string $class)
{
    ob_start(); ?>
?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class <?= $class ?>Test extends TestCase
{
    /** @var <?= $class ?> */
    private $<?= strtolower($class) ?>;

    protected function setUp()
    {
        $this-><?= strtolower($class) ?> = new <?= $class ?>();
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
<?php
    return '<'.ob_get_clean();
}
