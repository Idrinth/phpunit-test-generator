# Test-Generator for PHPUnit

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/cabd3fc70be847d4b2f28c9472598c61)](https://www.codacy.com/app/Idrinth/phpunit-test-generator?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Idrinth/phpunit-test-generator&amp;utm_campaign=Badge_Grade) [![Codacy Badge](https://api.codacy.com/project/badge/Coverage/cabd3fc70be847d4b2f28c9472598c61)](https://www.codacy.com/app/Idrinth/phpunit-test-generator?utm_source=github.com&utm_medium=referral&utm_content=Idrinth/phpunit-test-generator&utm_campaign=Badge_Coverage) [![Build Status](https://travis-ci.org/Idrinth/phpunit-test-generator.svg?branch=master)](https://travis-ci.org/Idrinth/phpunit-test-generator)
[![CodeFactor](https://www.codefactor.io/repository/github/idrinth/phpunit-test-generator/badge)](https://www.codefactor.io/repository/github/idrinth/phpunit-test-generator)

Generates tests for phpunit based on namespaces listed for autoloading in your composer.json. Existing files will NOT be overwritten, so if you need the redone, move or delete them.

## Example

This is a generated testfile for the actual Controller of this library.

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
