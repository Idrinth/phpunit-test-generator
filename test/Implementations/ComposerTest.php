<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\Composer;
use ReflectionClass;
use ReflectionMethod;

class ComposerTest extends TestCaseImplementation
{
    /**
     * @param array $data
     * @return Composer
     **/
    protected function getInstance($data)
    {
        $mock = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\JsonFile')->getMock();
        $mock->expects($this->exactly(2))
            ->method('getPath')
            ->willReturn('#base');
        $mock->expects($this->once())
            ->method('getContent')
            ->willReturn($data);
        return new Composer($mock);
    }

    /**
     * @return array
     */
    public function provideGetNamespacesToFolders()
    {
        return array(
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8')
                ),
                array(),
                array(),
            ),
            array(
                array(
                    'require-dev'=>array('phpunit/phpunit' => '^4.8'),
                    'autoload'=>array('psr-4'=>array('MyTest' => 'abc')),
                    'autoload-dev'=>array('psr-0'=>array('MyTestTest' => 'abcd'))
                ),
                array('MyTest' => '#base'.DIRECTORY_SEPARATOR.'abc'),
                array('MyTestTest' => '#base'.DIRECTORY_SEPARATOR.'abcd'),
            ),
        );
    }

    /**
     * From Composer
     * @test
     * @dataProvider provideGetNamespacesToFolders
     **/
    public function testGetNamespacesToFolders($composer, $prod, $dev)
    {
        $instance = $this->getInstance($composer);
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            $prod,
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
        $this->assertEquals(
            $dev,
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
        $instance = $this->getInstance(array('require-dev'=>array('phpunit/phpunit' => '^4.8')));
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
