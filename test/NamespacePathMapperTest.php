<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\NamespacePathMapper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class NamespacePathMapperTest extends TestCase
{
    /**
     * @test
     * @expectedException InvalidArgumentException
     */
    public function testConstructWithoutFile()
    {
        new NamespacePathMapper(new SplFileInfo(__FILE__));
    }

    /**
     * @test
     */
    public function testGetTestNamespaceForNamespace()
    {
        $object = new NamespacePathMapper(new SplFileInfo(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json'));
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
        $object = new NamespacePathMapper(new SplFileInfo(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json'));
        $namespace = 'De\Idrinth\TestGenerator\NamespacePathMapper';
        $this->assertInstanceOf('SplFileInfo', $object->getTestFileForNamespacedClass($namespace));
        $this->assertEquals(__FILE__, $object->getTestFileForNamespacedClass($namespace)->getPathname());
        $this->assertEquals(
            __DIR__.DIRECTORY_SEPARATOR.'NamespacePathMapper'.DIRECTORY_SEPARATOR.'ExampleTest.php',
            $object->getTestFileForNamespacedClass($namespace.'\Example')->getPathname()
        );
    }
}
