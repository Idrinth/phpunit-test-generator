<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\MethodFactory;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\TypeResolver;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;
use De\Idrinth\TestGenerator\Interfaces\Type;

class MethodFactoryTest extends TestCase
{
    /**
     * @return array
     */
    public function provideCreate()
    {
        return array(
            array(new ClassMethod('name'), 'name', array(), new UnknownType(), array())
        );
    }

    /**
     * @return TypeResolver
     */
    private function getTypeResolverInstance()
    {
        $resolver = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TypeResolver')
            ->getMock();
        $resolver->expects($this->once())
            ->method('toType')
            ->willReturn(new UnknownType());
        return $resolver;
    }

    /**
     * @return DocBlockParser
     */
    private function getDocParserInstance()
    {
        $doc = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\DocBlockParser')
            ->getMock();
        $doc->expects($this->once())
            ->method('getExceptions')
            ->willReturn(array());
        $doc->expects($this->once())
            ->method('getParams')
            ->willReturn(array());
        return $doc;
    }

    /**
     * @param ClassMethod $method
     * @param string $name
     * @param Type[] $params
     * @param Type $return
     * @param Type[] $exceptions
     * @test
     * @dataProvider provideCreate
     */
    public function testCreate(ClassMethod $method, $name, array $params, Type $return, array $exceptions)
    {
        $factory = new MethodFactory($this->getDocParserInstance());
        $result = $factory->create($this->getTypeResolverInstance(), $method);
        $this->assertEquals($name, $result->getName());
        $this->assertTypeEquals($return, $result->getReturn());
        $this->assertArrayEquals($exceptions, $result->getExceptions());
        $this->assertArrayEquals($params, $result->getParams());
    }

    /**
     * @param Type $expected
     * @param Type $actual
     */
    private function assertTypeEquals(Type $expected, $actual)
    {
        $this->assertInternalType('object', $actual);
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\Type', $actual);
        $this->assertEquals($expected->getClassName(), $actual->getClassName());
        $this->assertEquals($expected->getItemType(), $actual->getItemType());
        $this->assertEquals($expected->getType(), $actual->getType());
        $this->assertEquals($expected->isComplex(), $actual->isComplex());
    }

    /**
     * @param array $expected
     * @param Type[] $actual
     */
    private function assertArrayEquals(array $expected, $actual)
    {
        $this->assertInternalType('array', $actual);
        $this->assertCount(count($expected), $actual);
        foreach ($expected as $key => $value) {
            $this->assertTrue(isset($actual[$key]));
            $this->assertTypeEquals($value, $actual[$key]);
        }
    }
}
