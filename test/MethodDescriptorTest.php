<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\MethodDescriptor;
use PHPUnit\Framework\TestCase;

class MethodDescriptorTest extends TestCase
{
    /**
     * @return MethodDescriptor
     */
    private function getTest1()
    {
        return new MethodDescriptor('test1', array('string', 'MyClass'), 'YourClass');
    }

    /**
     * @return MethodDescriptor
     */
    private function getTest2()
    {
        return new MethodDescriptor('test2', array('int', 'float|double','boolean','int|double'), 'bool');
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $this->assertEquals('test1', $this->getTest1()->getName());
        $this->assertEquals('test2', $this->getTest2()->getName());
    }

    /**
     * @test
     */
    public function testGetParams()
    {
        $test1 = $this->getTest1()->getParams();
        $this->assertCount(2, $test1);
        $this->assertEquals('string', $test1[0]);
        $this->assertEquals('object', $test1[1]);
        $test2 = $this->getTest2()->getParams();
        $this->assertCount(4, $test2);
        $this->assertEquals('integer', $test2[0]);
        $this->assertEquals('float', $test2[1]);
        $this->assertEquals('boolean', $test2[2]);
        $this->assertEquals('float', $test2[3]);
    }

    /**
     * @test
     */
    public function testGetReturn()
    {
        $this->assertEquals('object', $this->getTest1()->getReturn());
        $this->assertEquals('boolean', $this->getTest2()->getReturn());
    }

    /**
     * @test
     */
    public function testGetReturnClass()
    {
        $this->assertEquals('YourClass', $this->getTest1()->getReturnClass());
        $this->assertNull($this->getTest2()->getReturnClass());
    }
}