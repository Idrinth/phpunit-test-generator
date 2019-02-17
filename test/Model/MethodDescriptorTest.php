<?php

namespace De\Idrinth\TestGenerator\Test\Model;

use De\Idrinth\TestGenerator\Model\MethodDescriptor;
use De\Idrinth\TestGenerator\Model\Type\ClassType;
use De\Idrinth\TestGenerator\Model\Type\SimpleType;
use De\Idrinth\TestGenerator\Model\Type\UnknownType;
use PHPUnit\Framework\TestCase;

class MethodDescriptorTest extends TestCase
{
    /**
     * @return MethodDescriptor
     */
    private function getTest1()
    {
        return new MethodDescriptor(
            'test1',
            array(new SimpleType('string'), new ClassType('MyClass')),
            new ClassType('YourClass')
        );
    }

    /**
     * @return MethodDescriptor
     */
    private function getTest2()
    {
        return new MethodDescriptor(
            'test2',
            array(new SimpleType('integer'), new UnknownType(), new SimpleType('boolean'), new UnknownType()),
            new SimpleType('boolean'),
            true,
            array(new ClassType('AClass'))
        );
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
        $this->assertEquals('string', $test1[0]->getType());
        $this->assertEquals('object', $test1[1]->getType());
        $test2 = $this->getTest2()->getParams();
        $this->assertCount(4, $test2);
        $this->assertEquals('integer', $test2[0]->getType());
        $this->assertEquals('unknown', $test2[1]->getType());
        $this->assertEquals('boolean', $test2[2]->getType());
        $this->assertEquals('unknown', $test2[3]->getType());
    }

    /**
     * @test
     */
    public function testGetReturn()
    {
        $this->assertEquals('object', $this->getTest1()->getReturn()->getType());
        $this->assertEquals('boolean', $this->getTest2()->getReturn()->getType());
    }

    /**
     * @test
     */
    public function testIsStatic()
    {
        $this->assertFalse($this->getTest1()->isStatic());
        $this->assertTrue($this->getTest2()->isStatic());
    }

    /**
     * @test
     */
    public function testGetExceptions()
    {
        $this->assertEmpty($this->getTest1()->getExceptions());
        $exceptions = $this->getTest2()->getExceptions();
        $this->assertCount(1, $exceptions);
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Model\Type', $exceptions[0]);
        $this->assertTrue($exceptions[0]->isComplex());
    }
}
