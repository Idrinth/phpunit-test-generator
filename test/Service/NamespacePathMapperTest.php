<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use De\Idrinth\TestGenerator\Service\NamespacePathMapper;
use De\Idrinth\TestGenerator\File\TargetPhpFile;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use UnexpectedValueException;

class NamespacePathMapperTest extends TestCase
{
    /**
     * @return NamespacePathMapper
     */
    private function getInstance()
    {
        $composer = $this
            ->getMockBuilder('De\Idrinth\TestGenerator\Model\Composer')
            ->disableOriginalConstructor()
            ->getMock();
        $composer->expects($this->once())
            ->method('getDevelopmentNamespacesToFolders')
            ->willReturn(array(
                'De\Idrinth\TestGenerator\Test' => 'test',
                'De\Test\Idrinth\AnyTestGenerator' => 'test'
            ));
        $composer->expects($this->once())
            ->method('getProductionNamespacesToFolders')
            ->willReturn(array(
                'De\Idrinth\TestGenerator' => 'src',
                'De\Idrinth\AnyTestGenerator' => 'src'
            ));
        return new NamespacePathMapper($composer, '');
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
        $this->assertEquals(
            'De\Test\Idrinth\AnyTestGenerator\Abc',
            $object->getTestNamespaceForNamespace('De\Idrinth\AnyTestGenerator\Abc')
        );
    }

    /**
     * @test
     * @expectedException UnexpectedValueException
     */
    public function testGetTestNamespaceForNamespaceThrowsUnexpectedValueException()
    {
        $this->getInstance()->getTestNamespaceForNamespace('Der\Idrinth\hat\einen\Test-Generator');
    }

    /**
     * @test
     */
    public function testGetTestFileForNamespacedClass()
    {
        $object = $this->getInstance();
        $namespace = 'De\Idrinth\TestGenerator\NamespacePathMapper';
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\File\TargetPhpFile',
            $object->getTestFileForNamespacedClass($namespace)
        );
        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, array('test', 'NamespacePathMapperTest.php')),
            $this->getPath($object->getTestFileForNamespacedClass($namespace))
        );
        $this->assertEquals(
            implode(DIRECTORY_SEPARATOR, array('test', 'NamespacePathMapper', 'ExampleTest.php')),
            $this->getPath($object->getTestFileForNamespacedClass($namespace.'\Example'))
        );
    }

    /**
     * @param TargetPhpFile $file
     * @return string
     */
    private function getPath(TargetPhpFile $file)
    {
        $class = new ReflectionClass('De\Idrinth\TestGenerator\File\TargetPhpFile');
        $property = $class->getProperty('file');
        $property->setAccessible(true);
        return $property->getValue($file);
    }
}
