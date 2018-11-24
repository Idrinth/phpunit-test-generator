<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ClassDescriptor;
use PHPUnit\Framework\TestCase;

class ClassDescriptorTest extends TestCase
{
    private static $method = 'De\Idrinth\TestGenerator\Interfaces\MethodDescriptor';
    private function getConstructorMock()
    {
        return $this->getMockBuilder(self::$method)
            ->getMock();
    }
    /**
     * @return ClassDescriptor
     */
    protected function getClass1()
    {
        return new ClassDescriptor(
            'ClassOne',
            '',
            [],
            $this->getConstructorMock(),
            false,
            'My\Namespace\ClassTwo'
        );
    }

    /**
     * @return ClassDescriptor
     */
    protected function getClass2()
    {
        return new ClassDescriptor(
            'ClassTwo',
            'My\Namespace',
            [$this->getConstructorMock()],
            $this->getConstructorMock(),
            true
        );
    }

    /**
     * @test
     */
    public function testGetName()
    {
        $this->assertEquals('ClassOne', $this->getClass1()->getName());
        $this->assertEquals('ClassTwo', $this->getClass2()->getName());
    }

    /**
     * @test
     */
    public function testGetNamespace()
    {
        $this->assertEquals('', $this->getClass1()->getNamespace());
        $this->assertEquals('My\Namespace', $this->getClass2()->getNamespace());
    }

    /**
     * @test
     */
    public function testGetMethods()
    {
        $this->assertCount(0, $this->getClass1()->getMethods());
        $class2 = $this->getClass2()->getMethods();
        $this->assertCount(1, $class2);
        $this->assertInstanceOf(self::$method, $class2[0]);
    }

    /**
     * @test
     */
    public function testGetConstructor()
    {
        $this->assertInstanceOf(self::$method, $this->getClass1()->getConstructor());
        $this->assertInstanceOf(self::$method, $this->getClass2()->getConstructor());
    }

    /**
     * @test
     */
    public function testIsAbstract()
    {
        $this->assertFalse($this->getClass1()->isAbstract());
        $this->assertTrue($this->getClass2()->isAbstract());
    }

    /**
     * @test
     */
    public function testGetExtends()
    {
        $this->assertEquals('My\Namespace\ClassTwo', $this->getClass1()->getExtends());
        $this->assertNull($this->getClass2()->getExtends());
    }
}
