# 🔧 Test Generator 🔨

[![Travis branch](https://img.shields.io/travis/mihaeu/php-test-generator/develop.svg)](https://travis-ci.org/mihaeu/php-test-generator)
[![Codecov branch](https://img.shields.io/codecov/c/github/mihaeu/php-test-generator/develop.svg)](https://codecov.io/gh/mihaeu/php-test-generator)
![](https://img.shields.io/badge/PHP-7.1-brightgreen.svg)
![](https://img.shields.io/badge/PHP-7.0-yellow.svg)
![](https://img.shields.io/badge/PHP-5.6-yellow.svg)
![](https://img.shields.io/badge/PHP-5.5-yellow.svg)

> Generate test cases for existing files

## Usage

```bash
bin/test-generator --help
```

```
Test-Generator

Usage:
  test-generator <file> [--php5] [--phpunit5] [--mockery] [--covers]

Options:
  --php5        Generate PHP5 compatible code [default:false].
  --phpunit5    Generate a test for PHPUnit 5 [default:false].
  --mockery     Generates mocks using Mockery [default:false].
  --covers      Adds the @covers annotation   [default:false].
```

## Installation

### Composer (PHP 7.1+)

```bash
# local install
composer require "mihaeu/test-generator:^1.0"

# global install
composer global require "mihaeu/test-generator:^1.0"
```

### Phar (PHP 5.5+)

Since I actually need to use this on 5.5 legacy projects (should work with 5.4 as well, but didn't test for it), I also release a phar file which works for older versions:

```bash
wget https://github.com/mihaeu/php-test-generator/releases/download/1.0.0/test-generator-1.0.0.phar
chmod +x test-generator-1.0.0.phar
```

**Please note that by doing this we should be disgusted at ourselves for not upgrading to PHP 7.1 (soon 7.2).**

### Git

```bash
git clone https://github.com/mihaeu/php-test-generator
cd php-test-generator
composer install
bin/test-generator --help
```

If you don't have PHP 7.1 installed you can run `bin/remove-php7-features` to convert the source files. I won't however except pull requests without PHP 7.1 support.

## Example

Given a PHP file like:

```php
<?php // A.php

class A {
    public function __construct(ClassA $classA) {}
}
```

Running `test-generator A.php` will produce a test including mocked dependencies:

```php
<?php declare(strict_types = 1);

use PHPUnit\Framework\TestCase;

class ATest extends TestCase
{
    /** @var A */
    private $a;

    /** @var ClassA | PHPUnit_Framework_MockObject_MockObject */
    private $classA;

    protected function setUp()
    {
        $this->classA = $this->createMock(ClassA::class);
        $this->a = new A(
            $this->classA
        );
    }

    public function testMissing()
    {
        $this->fail('Test not yet implemented');
    }
}
```

## LICENSE

> Copyright (c) 2017 Michael Haeuslmann
> 
> Permission is hereby granted, free of charge, to any person obtaining a copy
> of this software and associated documentation files (the "Software"), to deal
> in the Software without restriction, including without limitation the rights
> to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
> copies of the Software, and to permit persons to whom the Software is
> furnished to do so, subject to the following conditions:
> 
> The above copyright notice and this permission notice shall be included in all
> copies or substantial portions of the Software.
> 
> THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
> IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
> FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
> AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
> LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
> OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
> SOFTWARE.
