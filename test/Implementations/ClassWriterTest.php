<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper;
use De\Idrinth\TestGenerator\Interfaces\Renderer;
use PHPUnit\Framework\TestCase;

class ClassWriterTest extends TestCase
{
    /**
     * @param boolean $isWriteable
     * @param string $content
     * @return NamespacePathMapper
     */
    private function getMockedNamespacePathMapper($isWriteable)
    {
        $namespaces = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper')
            ->getMock();
        $namespaces->expects($this->any())
            ->method('getTestNamespaceForNamespace')
            ->willReturn('My\Tests');
        $file = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TargetPhpFile')->getMock();
        $file->expects($this->once())
            ->method('mayWrite')
            ->with()
            ->willReturn($isWriteable);
        $file->expects($isWriteable?$this->once():$this->never())
            ->method('write')
            ->with('rendered')
            ->willReturn(true);
        $namespaces->expects($this->any())
            ->method('getTestFileForNamespacedClass')
            ->willReturn($file);
        return $namespaces;
    }

    /**
     * @return MethodDescriptor
     */
    private function getMockedMethod()
    {
        $method = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor')
            ->getMock();
        $method->expects($this->any())
            ->method('getName')
            ->willReturn('method');
        $method->expects($this->any())
            ->method('getParams')
            ->willReturn(array());
        $method->expects($this->any())
            ->method('getReturn')
            ->willReturn(new UnknownType());
        return $method;
    }

    /**
     * @return ClassDescriptor
     */
    private function getMockedClassDescriptor()
    {
        $class = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassDescriptor')
            ->getMock();
        $class->expects($this->any())
            ->method('getName')
            ->willReturn('AbCdE');
        $class->expects($this->any())
            ->method('getNamespace')
            ->willReturn('My');
        $class->expects($this->any())
            ->method('getMethods')
            ->willReturn(array($this->getMockedMethod()));
        $class->expects($this->any())
            ->method('getConstructor')
            ->willReturn($this->getMockedMethod());
        return $class;
    }

    /**
     * @param boolean $isWriteable
     * @return Renderer
     */
    private function getMockedRenderer($isWriteable)
    {
        $environment = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Renderer')
            ->getMock();
        $environment->expects($isWriteable?$this->once():$this->never())
            ->method('render')
            ->willReturn('rendered');
        return $environment;
    }

    /**
     * @param boolean $isWriteable
     * @return ClassWriter
     */
    private function buildClassWriter($isWriteable)
    {
        return new ClassWriter(
            $this->getMockedNamespacePathMapper($isWriteable),
            $this->getMockedRenderer($isWriteable)
        );
    }

    /**
     * @return array
     */
    public function provideWrite()
    {
        return array(
            array(
                $this->buildClassWriter(true),
                true
            ),
            array(
                $this->buildClassWriter(false),
                false
            ),
        );
    }

    /**
     * @test
     * @dataProvider provideWrite
     * @param ClassWriter $writer
     * @param boolean $isWriteable
     */
    public function testWrite(ClassWriter $writer, $isWriteable)
    {
        $class = $this->getMockedClassDescriptor();
        $this->assertEquals($isWriteable, $writer->write($class, array()));
    }
}
