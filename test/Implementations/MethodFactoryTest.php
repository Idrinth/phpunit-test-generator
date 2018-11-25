<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\MethodFactory;
use De\Idrinth\TestGenerator\Implementations\Type\UnknownType;
use De\Idrinth\TestGenerator\Interfaces\DocBlockParser;
use De\Idrinth\TestGenerator\Interfaces\Type;
use De\Idrinth\TestGenerator\Interfaces\TypeResolver;
use PhpParser\Node\Stmt\ClassMethod;
use PHPUnit\Framework\TestCase;

class MethodFactoryTest extends TestCase
{
    /**
     * @return array
     */
    public function provideCreate()
    {
        return [
            $this->getClassMethodSet(),
            $this->getClassMethodSet(1),
            $this->getClassMethodSet(0, 1),
            $this->getClassMethodSet(1, 2),
            $this->getClassMethodSet(2, 3),
            $this->getClassMethodSet(3, 1),
        ];
    }

    private function getClassMethodSet($params = 0, $exceptions = 0)
    {
        $name = 'name'.time();
        return [
            new ClassMethod(
                $name,
                ['params' => $this->fillArray($params, json_decode('{"type":"hi"}'))]
            ),
            $this->getDocParserInstance($exceptions, $params),
            $this->getTypeResolverInstance(1 + $exceptions + $params),
            $name,
            $params,
            $exceptions
        ];
    }

    /**
     * @return TypeResolver
     */
    private function getTypeResolverInstance($calls = 0)
    {
        $resolver = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\TypeResolver')
            ->getMock();
        $resolver->expects($this->exactly($calls))
            ->method('toType')
            ->willReturn(new UnknownType());
        return $resolver;
    }

    /**
     * @return DocBlockParser
     */
    private function getDocParserInstance($exceptions = 0, $params = 0)
    {
        $doc = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\DocBlockParser')
            ->getMock();
        $doc->expects($this->once())
            ->method('getExceptions')
            ->willReturn($this->fillArray($exceptions, json_decode('{"type":"hi"}')));
        $doc->expects($this->once())
            ->method('getParams')
            ->willReturn($this->fillArray($params, json_decode('{"type":"hi"}')));
        return $doc;
    }

    /**
     * @param ClassMethod $method
     * @param DocBlockParser $parser
     * @param string $name
     * @param int $params
     * @param int $exceptions
     * @test
     * @dataProvider provideCreate
     */
    public function testCreate(
        ClassMethod $method,
        DocBlockParser $parser,
        TypeResolver $resolver,
        $name,
        $params,
        $exceptions
    ) {
        $factory = new MethodFactory($parser);
        $result = $factory->create($resolver, $method);
        $this->assertEquals($name, $result->getName());
        $this->assertTypeEquals($result->getReturn());
        $this->assertArrayEquals($exceptions, $result->getExceptions());
        $this->assertArrayEquals($params, $result->getParams());
    }

    /**
     * @param Type $actual
     */
    private function assertTypeEquals($actual)
    {
        $this->assertInternalType('object', $actual);
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\Type', $actual);
    }

    /**
     * @param int $expected
     * @param Type[] $actual
     */
    private function assertArrayEquals($expected, $actual)
    {
        $this->assertInternalType('array', $actual);
        $this->assertCount($expected, $actual);
        foreach ($actual as $value) {
            $this->assertTypeEquals($value);
        }
    }

    /**
     * @param int $amount
     * @param mixed $content
     * @return array
     */
    private function fillArray($amount, $content)
    {
        if ($amount > 0) {
            return array_fill(0, $amount, $content);
        }
        return [];
    }
}
