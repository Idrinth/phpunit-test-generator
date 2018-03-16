<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class ClassWriterTest extends TestCase
{
    /**
     * @var string
     */
    private $filename;

    /**
     * @return string
     */
    private function getFileName()
    {
        if (!$this->filename) {
            $this->filename = sys_get_temp_dir()
                .DIRECTORY_SEPARATOR
                .str_replace('\\', '_', __CLASS__)
                .DIRECTORY_SEPARATOR
                .'.'.md5(__FILE__.microtime().mt_rand().PHP_VERSION).'.php';
        }
        return $this->filename;
    }

    /**
     * @return NamespacePathMapper
     */
    private function getMockedNamespacePathMapper()
    {
        $this->filename = $this->getFileName();
        $namespaces = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper')
            ->getMock();
        $namespaces->expects($this->any())
            ->method('getTestNamespaceForNamespace')
            ->willReturn('My\Tests');
        $namespaces->expects($this->any())
            ->method('getTestFileForNamespacedClass')
            ->willReturn(new SplFileInfo($this->filename));
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
     * @test
     * @todo properly test
     */
    public function testWrite()
    {
        $writer = @new ClassWriter($this->getMockedNamespacePathMapper());//thanks to twig
        $this->assertTrue($writer->write($this->getMockedClassDescriptor(), array()));
        include_once $this->filename;
        $this->assertTrue(class_exists('My\Tests\AbCdETest'));
        $test = new \My\Tests\AbCdETest();
        $this->assertTrue(method_exists($test, 'testMethod'));
        $writer->write($this->getMockedClassDescriptor(), array());
        $this->assertFileExists($this->filename.'.'.date('YmdHi').'.old');
        @unlink($this->filename.'.'.date('YmdHi').'.old');
        $this->assertFileExists($this->filename);
        @unlink($this->filename);
    }
}
