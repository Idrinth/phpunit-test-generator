<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class ClassReaderTest extends TestCase
{
    private function getParserInstance(array $return = array())
    {
        $parser = $this->getMockBuilder('PhpParser\Parser')->setConstructorArgs(
            array(
                $this->getMockBuilder('PhpParser\Lexer')->getMock()
            )
        )->getMock();
        $parser->expects($this->once())
            ->method('parse')
            ->willReturn($return);
        return $parser;
    }

    /**
     * @return array
     */
    public function provideParseAndGetResults()
    {
        $factory = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\MethodFactory')->getMock();
        $file = new SplFileInfo(__FILE__);
        return array(
            array(new ClassReader($factory, $this->getParserInstance()), $file, array())
        );
    }

    /**
     * @param ClassReader $reader
     * @param array $result
     * @dataProvider provideParseAndGetResults
     * @test
     */
    public function testParseAndGetResults(ClassReader $reader, SplFileInfo $file, array $result)
    {
        $this->assertCount(0, $reader->getResults());
        $reader->parse($file);
        $this->assertArrayValuesEqual($result, $reader->getResults());
    }

    /**
     * @param array $expected
     * @param \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor[] $actual
     */
    private function assertArrayValuesEqual(array $expected, $actual)
    {
        $this->assertInternalType('array', $actual);
        $this->assertEquals(count($expected), count($actual));
        foreach ($expected as $pos => $value) {
            $this->assertInstanceOf('\De\Idrinth\TestGenerator\Interfaces\ClassDescriptor', $actual[$pos]);
            $this->assertEquals($value['name'], $actual[$pos]->getName());
            $this->assertEquals($value['namespace'], $actual[$pos]->getNamespace());
            $this->assertEquals($value['abstract'], $actual[$pos]->isAbstract());
            $this->assertEquals($value['extends'], $actual[$pos]->getExtends());
            $methods = $actual[$pos]->getMethods();
            $this->assertCount($value['methods'], $methods);
            foreach ($methods as $method) {
                $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\MethodDescriptor', $method);
            }
        }
    }
}
