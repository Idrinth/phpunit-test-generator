<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassWriter;
use PHPUnit\Framework\TestCase;

class ClassWriterTest extends TestCase
{
    /**
     * @test
     */
    public function testWrite()
    {
        $namespaces = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper')
            ->getMock();
        $class = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassDescriptor')
            ->getMock();
        $writer = new ClassWriter($namespaces);
        $writer->write($class);
        $this->assertTrue(true);//@todo properly test
    }
}