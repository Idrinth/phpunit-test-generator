<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use De\Idrinth\TestGenerator\Service\TestClassDecider;
use PHPUnit\Framework\TestCase;

class TestClassDeciderTest extends TestCase
{
    /**
     * @return array
     */
    public function provideGet()
    {
        $old = 'PHPUnit_Framework_TestCase';
        $new = 'PHPUnit\Framework\TestCase';
        return array(
            array('^1', false, $old),
            array('^2', false, $old),
            array('^3', false, $old),
            array('^4', false, $new),
            array('^5', false, $new),
            array('^6', false, $new),
            array('^7', false, $new),
            array('^3|^4|^5|^6', true),
            array('^1|^7', true),
            array('4.5.6|^7', true),
            array('^4|^7', false, $new),
            array('^7.0', false, $new),
        );
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
