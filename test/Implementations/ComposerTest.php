<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\Composer;
use ReflectionClass;
use ReflectionMethod;
use SplFileInfo;

class ComposerTest extends TestCaseImplementation
{
    /**
     * @param string $file
     * @return Composer
     **/
    protected function getInstance($file)
    {
        return new Composer(new SplFileInfo($file));
    }

    /**
     * From Composer
     * @test
     **/
    public function testGetProductionNamespacesToFolders()
    {
        $base = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR;
        $instance = $this->getInstance($base.'composer.json');
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            array("De\\Idrinth\\TestGenerator" => "{$base}src"),
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
    }

    /**
     * From Composer
     * @test
     **/
    public function testGetDevelopmentNamespacesToFolders()
    {
        $base = dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR;
        $instance = $this->getInstance($base.'composer.json');
        $this->assertInternalType(
            'array',
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            array("De\\Idrinth\\TestGenerator\\Test" => "{$base}test"),
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
    }

    /**
     * @return array
     * @throws \ReflectionException
     */
    public function provideGetTestClass()
    {
        $old = 'PHPUnit_Framework_TestCase';
        $new = 'PHPUnit\Framework\TestCase';
        $instance = new Composer(new SplFileInfo(dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'composer.json'));
        $class = new ReflectionClass($instance);
        $method = $class->getMethod('findTestClass');
        $method->setAccessible(true);
        return array(
            array($instance, $method, '^1', false, $old),
            array($instance, $method, '^2', false, $old),
            array($instance, $method, '^3', false, $old),
            array($instance, $method, '^4', false, $new),
            array($instance, $method, '^5', false, $new),
            array($instance, $method, '^6', false, $new),
            array($instance, $method, '^7', false, $new),
            array($instance, $method, '^3|^4|^5|^6', true),
            array($instance, $method, '^1|^7', true),
            array($instance, $method, '4.5.6|^7', true),
            array($instance, $method, '^4|^7', false, $new),
        );
    }

    /**
     * @test
     * @dataProvider provideGetTestClass
     * @param Composer $instance
     * @param ReflectionMethod $method
     * @param string $constraint
     * @param boolean $willThrow
     * @param string $return
     */
    public function testGetTestClass(
        Composer $instance,
        ReflectionMethod $method,
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
        $result = $method->invoke($instance, $constraint);
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
