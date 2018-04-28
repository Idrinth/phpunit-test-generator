<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor;
use De\Idrinth\TestGenerator\Interfaces\Composer;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper;
use De\Idrinth\TestGenerator\Interfaces\Renderer;
use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class ClassWriterTest extends TestCase
{
    /**
     * @var vfsStreamDirectory
     */
    private $root;
    /**
     * @return string
     */
    private function getFileName()
    {
        return vfsStream::url('/root/test/folder/file.php');
    }

    /**
     * init vfsStream
     */
    protected function setUp()
    {
        $this->root = vfsStream::setup();
    }

    /**
     * @return NamespacePathMapper
     */
    private function getMockedNamespacePathMapper()
    {
        $namespaces = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper')
            ->getMock();
        $namespaces->expects($this->any())
            ->method('getTestNamespaceForNamespace')
            ->willReturn('My\Tests');
        $namespaces->expects($this->any())
            ->method('getTestFileForNamespacedClass')
            ->willReturn(new SplFileInfo($this->getFileName()));
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
     * @return array
     */
    public function provideWrite()
    {
        return array(
            array(
                new ClassWriter(
                    $this->getMockedNamespacePathMapper(),
                    $this->getMockedRenderer(),
                    $this->getMockedComposer(),
                    'replace'
                ),
                true,
                false
            ),
            array(
                new ClassWriter(
                    $this->getMockedNamespacePathMapper(),
                    $this->getMockedRenderer(),
                    $this->getMockedComposer(),
                    'skip'
                ),
                false,
                false
            ),
            array(
                new ClassWriter(
                    $this->getMockedNamespacePathMapper(),
                    $this->getMockedRenderer(),
                    $this->getMockedComposer(),
                    'move'
                ),
                false,
                true
            )
        );
    }

    /**
     * @test
     * @dataProvider provideWrite
     * @param ClassWriter $writer
     * @param type $willChange
     * @param type $willMove
     */
    public function testWrite(ClassWriter $writer, $willChange, $willMove)
    {
        $this->assertTrue($writer->write($this->getMockedClassDescriptor(), array()));
        $created = $this->checkFile($this->getFileName(), true);
        sleep(1);
        $this->assertEquals($willChange||$willMove, $writer->write($this->getMockedClassDescriptor(), array()));
        $this->checkFile($this->getFileName().'.'.date('YmdHi').'.old', $willMove);
        $this->assertEquals($willChange, $created !== $this->checkFile($this->getFileName(), true));
    }

    /**
     * check existance and return filemtime
     * @param string $path
     * @param string $exists
     * @return int
     */
    private function checkFile($path, $exists)
    {
        $this->assertEquals($exists, is_file($path));
        if ($exists) {
            $this->assertEquals('rendered', file_get_contents($path));
            return filemtime($path);
        }
        return 0;
    }
}
