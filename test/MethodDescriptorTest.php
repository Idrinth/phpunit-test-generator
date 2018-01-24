<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor;
use PHPUnit\Framework\TestCase;

class MethodDescriptorTest extends TestCase
{
    private function getTest1()
    {
        return new MethodDescriptor('test1', array('string', 'MyClass'), 'YourClass');
    }
    private function getTest2()
    {
        return new MethodDescriptor('test2', array('int', 'float|double','boolean'), 'bool');
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
        $this->assertCount(2, $this->getTest1()->getParams());
        $this->assertCount(3, $this->getTest2()->getParams());
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