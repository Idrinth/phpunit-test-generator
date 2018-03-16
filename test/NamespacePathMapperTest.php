<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper;
use PHPUnit\Framework\TestCase;

class NamespacePathMapperTest extends TestCase
{
    /**
     * @return NamespacePathMapper
     */
    private function getInstance()
    {
        $composer = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\Composer')->getMock();
        $composer->expects($this->once())
            ->method('getDevelopmentNamespacesToFolders')
            ->willReturn(array('De\Idrinth\TestGenerator\Test' => 'test'));
        $composer->expects($this->once())
            ->method('getProductionNamespacesToFolders')
            ->willReturn(array('De\Idrinth\TestGenerator' => 'src'));
        return new NamespacePathMapper($composer);
    }

    /**
     * @test
     */
    public function testGetTestNamespaceForNamespace()
    {
        $object = $this->getInstance();
        $this->assertEquals(
            'De\Idrinth\TestGenerator\Test',
            $object->getTestNamespaceForNamespace('De\Idrinth\TestGenerator')
        );
        $this->assertEquals(
            'De\Idrinth\TestGenerator\Test\Abc',
            $object->getTestNamespaceForNamespace('De\Idrinth\TestGenerator\Abc')
        );
        $this->assertEquals(
            'De\Idrinth\TestGenerator\Test\Abc',
            $object->getTestNamespaceForNamespace('De\Idrinth\TestGenerator\Test\Abc')
        );
    }

    /**
     * @test
     */
    public function testGetTestFileForNamespacedClass()
    {
        $object = $this->getInstance();
        $namespace = 'De\Idrinth\TestGenerator\NamespacePathMapper';
        $this->assertInstanceOf('SplFileInfo', $object->getTestFileForNamespacedClass($namespace));
        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, array('test', 'NamespacePathMapperTest.php')),
            $object->getTestFileForNamespacedClass($namespace)->getPathname()
        );
        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, array('test', 'NamespacePathMapper', 'ExampleTest.php')),
            $object->getTestFileForNamespacedClass($namespace.'\Example')->getPathname()
        );
    }
}
