<?php

namespace Mihaeu\TestGenerator;

function phpunit6Template()
{
    return function ($class, array $parameters = []) {
    ob_start(); ?>
?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class <?= $class ?>Test extends TestCase
{
    /** @var <?= $class ?> */
    private $<?= lcfirst($class) ?>;

<?php if (!empty($parameters)) : ?>
<?php foreach ($parameters as $name => $dependency) : ?>
    /** @var <?= $dependency->type() ?: 'mixed' ?><?php if (!$dependency->isScalar()) :?> | PHPUnit_Framework_MockObject_MockObject<?php endif; ?> */
    private $<?= $name ?>;

<?php endforeach; ?>
<?php endif; ?>
    protected function setUp()
    {
<?php if (empty($parameters)) :?>
        $this-><?= lcfirst($class) ?> = new <?= $class ?>();
<?php else : ?>
<?php foreach ($parameters as $name => $dependency) : ?>
<?php if ($dependency->value() !== null) : ?>
        $this-><?= $name ?> = <?= $dependency->value() ?>;
<?php elseif (!$dependency->type()) : ?>
        $this-><?= $name ?> = null;
<?php else : ?>
        $this-><?= $name ?> = $this->createMock(<?= $dependency->type() ?>::class);
<?php endif; ?>
<?php endforeach; ?>
        $this-><?= lcfirst($class) ?> = new <?= $class ?>(
            <?= implode(",\n            ", array_map(function ($x) {return '$this->'.$x;}, array_keys($parameters))) ?>

        );
<?php endif; ?>
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
<?php
    return '<'.ob_get_clean();
    };
}
