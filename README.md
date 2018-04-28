# Test-Generator for PHPUnit

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cabd3fc70be847d4b2f28c9472598c61)](https://www.codacy.com/app/Idrinth/phpunit-test-generator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Idrinth/phpunit-test-generator&amp;utm_campaign=Badge_Grade)
[![Codacy Badge](https://api.codacy.com/project/badge/Coverage/cabd3fc70be847d4b2f28c9472598c61)](https://www.codacy.com/app/Idrinth/phpunit-test-generator?utm_source=github.com&utm_medium=referral&utm_content=Idrinth/phpunit-test-generator&utm_campaign=Badge_Coverage)
[![Build Status](https://travis-ci.org/Idrinth/phpunit-test-generator.svg?branch=master)](https://travis-ci.org/Idrinth/phpunit-test-generator)
[![CodeFactor](https://www.codefactor.io/repository/github/idrinth/phpunit-test-generator/badge)](https://www.codefactor.io/repository/github/idrinth/phpunit-test-generator)
[![BCH compliance](https://bettercodehub.com/edge/badge/Idrinth/phpunit-test-generator?branch=master)](https://bettercodehub.com/)

## Command & Options

```
php bin/generate-tests.php --dir=/path/to/alternative/dir --replace
```

### dir

Dir is optional and will default to the current working directory. Pick the directory that the composer.json is in.

### replace

If set old testfiles will be overwritten, if not set they will be renamed instead. Usually you shouldn't need this.

## How does it work?

The composer.json at the working directory(or the supplied dir) will pe parsed for namespaces(psr 0 and 4), the folders targeted by none-dev namespaces will be searched for classes and test classes will be generated in the appropriate development-autoloading-folder.
To determine the testing class to be used, the phpunit dev-dependency is parsed, so you need to supply a constraint to make the generation work.

## Generated Test - Example

This is a generated testfile for the actual Controller of this library. The intention is to generate PSR2-compatible code, obviously that might not yet be the case everywhere. If you find one where it fails, feel free to file a bug.

```php
<?php

namespace De\Idrinth\TestGenerator\Test;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Controller;

/**
 * this is an automatically generated skeleton for testing Controller
 * @todo actually test
 **/
class ControllerTest extends TestCaseImplementation
{
    /**
     * @return Controller
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new Controller(
            $this->getMockBuilder('Symfony\Component\Finder\Finder')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassReader')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassWriter')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Composer')->getMock(),
            null
        );
    }

    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     **/
    public function testInit ()
    {
        $instance = $this->getInstance();
        $return = $instance->init();

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );

        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Controller',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Controller'
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     **/
    public function testRun ()
    {
        $instance = $this->getInstance();
        $return = $instance->run();

        $this->assertInternalType(
            'null',
            $return,
            'Return didn\'t match expected type null'
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
```
