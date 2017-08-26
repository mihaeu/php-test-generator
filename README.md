# 🔧 Test Generator 🔨

[![Travis branch](https://img.shields.io/travis/mihaeu/php-test-generator/develop.svg)](https://travis-ci.org/mihaeu/php-test-generator)

> Generate test cases for existing files

## Usage

```bash
bin/test-generator --help
```

```
Test Generator

Usage:
  test-generator <file>
```

## Installation

### Git

```bash
git clone https://github.com/mihaeu/php-test-generator
cd php-test-generator
composer install
bin/test-generator --help
```

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
