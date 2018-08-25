<?php

declare(strict_types=1);

function x(
    bool $x1,
    int $x3,
    float $x4,
    string $x6,
    Throwable $t,
    iterable $i
): iterable {
    $xs= [];
    $x = $xs[1] ?? 5;
}

class X {
    private const y = 3;

    public function x(...$xs): void
    {
        $y = array_merge(...$xs);
    }
}
