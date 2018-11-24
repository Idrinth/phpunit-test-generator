<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\TestClassDecider;
use PHPUnit\Framework\TestCase as TestCaseImplementation;

class TestClassDeciderTest extends TestCaseImplementation
{
    /**
     * @return array
     */
    public function provideGet()
    {
        $old = 'PHPUnit_Framework_TestCase';
        $new = 'PHPUnit\Framework\TestCase';
        return [
            ['^1', false, $old],
            ['^2', false, $old],
            ['^3', false, $old],
            ['^4', false, $new],
            ['^5', false, $new],
            ['^6', false, $new],
            ['^7', false, $new],
            ['^3|^4|^5|^6', true],
            ['^1|^7', true],
            ['4.5.6|^7', true],
            ['^4|^7', false, $new],
            ['^7.0', false, $new],
        ];
    }

    /**
     * @test
     * @dataProvider provideGet
     * @param string $constraint
     * @param boolean $willThrow
     * @param string $return
     */
    public function testGet(
        $constraint,
        $willThrow,
        $return = ''
    ) {
        if ($willThrow) {
            $this->exceptionIsExpected(
                'InvalidArgumentException',
                'No possibility to determine PHPunit TestCase class found'
            );
        }
        $instance = new TestClassDecider();
        $result = $instance->get($constraint);
        if (!$willThrow) {
            $this->assertEquals($return, $result);
        }
    }

    /**
     * Wraps the changes to exception testing to cover all supported phpunit-versions
     * @param string $class
     * @param string $message
     * @return void
     */
    private function exceptionIsExpected($class, $message)
    {
        if (!method_exists($this, 'expectException')) {
            return $this->setExpectedException($class, $message);
        }
        $this->expectException($class);
        $this->expectExceptionMessage($message);
    }
}
