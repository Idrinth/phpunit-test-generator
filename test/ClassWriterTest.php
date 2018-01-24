<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use PHPUnit\Framework\TestCase;

class ClassWriterTest extends TestCase
{
    private $filename;
    private function getMockedNamespacePathMapper()
    {
        $this->filename = $this->filename ?:
            sys_get_temp_dir().DIRECTORY_SEPARATOR.str_replace('\\', '_', __CLASS__).'.tmp';
        $namespaces = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper')
            ->getMock();
        $namespaces->expects($this->any())
            ->method('getTestNamespaceForNamespace')
            ->willReturn('My\Tests');
        $namespaces->expects($this->any())
            ->method('getTestFileForNamespacedClass')
            ->willReturn(new \SplFileInfo($this->filename));
        return $namespaces;
    }
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
            ->willReturn('null');
        $method->expects($this->any())
            ->method('getReturnClass')
            ->willReturn(null);
        return $method;
    }
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
     * @test
     * @todo properly test
     */
    public function testWrite()
    {
        $writer = new ClassWriter($this->getMockedNamespacePathMapper());
        $this->assertTrue($writer->write($this->getMockedClassDescriptor()));
        include_once $this->filename;
        $this->assertTrue(class_exists('My\Tests\AbCdETest'));
        $test = new \My\Tests\AbCdETest();
        $this->assertTrue(method_exists($test, 'testMethod'));
        $lastModified = filemtime($this->filename);
        $writer->write($this->getMockedClassDescriptor());
        $this->assertEquals($lastModified, filemtime($this->filename));
        unlink($this->filename);
    }
}
