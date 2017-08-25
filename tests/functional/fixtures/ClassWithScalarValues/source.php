<?php

class A {
    public function __construct(
        $x,
        bool $bool,
        int $int,
        string $string,
        $otherString = 'abc',
        $otherInt = 999,
        $otherFloat = 3.1415,
        $xs = [],
        array $array,
        $caseInsensitive = TRUE,
        $fixed = PHP_EOL
    ) {}
}
