<?php

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

class PhpBackportingTest extends TestCase
{
    public function testBackportsScalarTypeDeclarationsAndVoidAndCoalesce(): void
    {
        $cmd = __DIR__ . '/../../tools/remove-php7-features ';
        $example = __DIR__ . '/../resources/php7-to-php55-example/example.php';
        $actualFile = __DIR__ . '/../resources/php7-to-php55-example/actual.php';
        exec(PHP_BINARY . " $cmd '$example' '$actualFile'", $output, $statusCode);
        assertSame(0, $statusCode, 'Conversion failed');

        $actual = trim(file_get_contents($actualFile));
        $expected = trim(file_get_contents(__DIR__ . '/../resources/php7-to-php55-example/expected.php'));

        assertSame($expected, $actual);
    }
}
