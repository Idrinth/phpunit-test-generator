<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\ClassReader;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptorFactory;
use PhpParser\Node\Name;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\Namespace_;
use PhpParser\Node\Stmt\Use_;
use PhpParser\Parser;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

class ClassReaderTest extends TestCase
{
    /**
     * @param array $return
     * @return Parser
     */
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
     * @param int $create
     * @param int $uses
     * @return ClassDescriptorFactory
     */
    private function getFactory($create = 0, $uses = 0)
    {
        $factory = $this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassDescriptorFactory')->getMock();
        $factory->expects($this->exactly($create))
            ->method('create')
            ->willReturn($this->getMockBuilder('De\Idrinth\TestGenerator\Interfaces\ClassDescriptor')->getMock());
        $factory->expects($this->exactly($uses))
            ->method('addUse');
        return $factory;
    }

    /**
     * @return array
     */
    public function provideParseAndGetResults()
    {
        $file = new SplFileInfo(__FILE__);
        return array(
            array(
                new ClassReader($this->getFactory(), $this->getParserInstance()),
                $file,
                array()
            ),
            array(
                new ClassReader($this->getFactory(1), $this->getParserInstance(array(new Class_('n1')))),
                $file,
                array('n1')
            ),
            array(
                new ClassReader(
                    $this->getFactory(2),
                    $this->getParserInstance(array(new Class_('n1'), new Class_('n2')))
                ),
                $file,
                array('n1', 'n2')
            ),
            array(
                new ClassReader(
                    $this->getFactory(2),
                    $this->getParserInstance(array(
                        new Namespace_(
                            new Name('prefix'),
                            array(new Class_('n1'), new Class_('n2'))
                        )
                    ))
                ),
                $file,
                array('prefix\\n1', 'prefix\\n2')
            ),
            array(
                new ClassReader(
                    $this->getFactory(2, 1),
                    $this->getParserInstance(array(
                        new Use_(array()),
                        new Class_('n1'),
                        new Class_('n2')
                    ))
                ),
                $file,
                array('n1', 'n2')
            )
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
        $actual = $reader->getResults();
        $this->assertInternalType('array', $actual);
        $this->assertEquals(count($result), count($actual));
        foreach ($result as $name) {
            $this->assertTrue(isset($actual[$name]));
            $this->assertInstanceOf('De\Idrinth\TestGenerator\Interfaces\ClassDescriptor', $actual[$name]);
        }
    }
}
