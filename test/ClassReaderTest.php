<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use PHPUnit\Framework\TestCase;

class ClassReaderTest extends TestCase
{
    /**
     * @test
     */
    public function testParseAndGetResults()
    {
        $object = new ClassReader;
        $this->assertCount(0, $object->getResults());
        $object->parse(new \SplFileInfo(__FILE__));
        $this->assertCount(1, $object->getResults());
        $object->parse(new \SplFileInfo(__FILE__));
        $results = $object->getResults();
        $this->assertCount(2, $results);
        $this->assertEquals('ClassReaderTest', $results[0]->getName());
        $this->assertEquals('De\Idrinth\TestGenerator\Test', $results[0]->getNamespace());
        $methods = $results[0]->getMethods();
        $this->assertCount(1, $methods);
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor', $results[0]->getConstructor());
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor', $methods[0]);
    }
}