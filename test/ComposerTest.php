<?php

namespace De\Idrinth\TestGenerator\Test;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\Composer;
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
        $base = dirname(__DIR__).DIRECTORY_SEPARATOR;
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
        $base = dirname(__DIR__).DIRECTORY_SEPARATOR;
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
     */
    public function provideGetTestClass()
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
        );
    }

    /**
     * @test
     * @dataProvider provideGetTestClass
     * @param string $constraint
     * @param boolean $willThrow
     * @param string $return
     * @throws \ReflectionException
     */
    public function testGetTestClass($constraint, $willThrow, $return = '')
    {
        if ($willThrow) {
            $this->setExpectedException(
                'InvalidArgumentException',
                'No possibility to determine PHPunit TestCase class found'
            );
        }
        $instance = new Composer(new SplFileInfo(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json'));
        $class = new \ReflectionClass($instance);
        $method = $class->getMethod('findTestClass');
        $result = $method->invoke($instance, $constraint);
        if (!$willThrow) {
            $this->assertEquals($return, $result);
        }
    }
}
