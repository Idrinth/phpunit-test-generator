<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper;
use De\Idrinth\TestGenerator\Interfaces\Renderer;
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
     * @return Renderer
     */
    private function getMockedRenderer()
    {
        $environment = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Renderer')
            ->getMock();
        $environment->expects($this->exactly(2))
            ->method('render')
            ->willReturn('rendered');
        return $environment;
    }

    /**
     * @return Composer
     */
    private function getMockedComposer()
    {
        $environment = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Composer')
            ->getMock();
        $environment->expects($this->exactly(2))
            ->method('getTestClass')
            ->willReturn('None');
        return $environment;
    }

    /**
     * @test
     * @todo properly test
     */
    public function testWrite()
    {
        $writer = new ClassWriter(
            $this->getMockedNamespacePathMapper(),
            $this->getMockedRenderer(),
            $this->getMockedComposer()
        );
        $this->assertTrue($writer->write($this->getMockedClassDescriptor(), array()));
        $this->assertEquals('rendered', file_get_contents($this->filename));
        $writer->write($this->getMockedClassDescriptor(), array());
        $this->assertFileExists($this->filename.'.'.date('YmdHi').'.old');
        @unlink($this->filename.'.'.date('YmdHi').'.old');
        $this->assertFileExists($this->filename);
        @unlink($this->filename);
    }
}
