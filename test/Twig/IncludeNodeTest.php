<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\IncludeNode;

class IncludeNodeTest extends TestCaseImplementation
{
    /**
     * @return IncludeNode
     **/
    protected function getInstance($indent)
    {
        return new IncludeNode(
            $this->getMockBuilder('Twig_Node_Expression')->getMock(),
            $this->getMockBuilder('Twig_Node_Expression')->getMock(),
            false,
            false,
            0,
            $indent
        );
    }

    /**
     * From IncludeNode
     * @test
     **/
    public function testCompileIndented()
    {
        $instance = $this->getInstance(1);
        $compiler = $this->getMockBuilder('Twig\Compiler')->disableOriginalConstructor()->getMock();
        $compiler->expects($this->exactly(1))
            ->method('write')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('subcompile')
            ->willReturnSelf();
        $compiler->expects($this->exactly(21))
            ->method('raw')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('repr')
            ->willReturnSelf();
        $compiler->expects($this->exactly(3))
            ->method('indent')
            ->willReturnSelf();
        $compiler->expects($this->exactly(3))
            ->method('outdent')
            ->willReturnSelf();
        $return = $instance->compile($compiler);

        $this->assertInternalType(
            'null',
            $return,
            'Return didn\'t match expected type null'
        );
    }

    /**
     * From IncludeNode
     * @test
     **/
    public function testCompileUnindented()
    {
        $instance = $this->getInstance(0);
        $compiler = $this->getMockBuilder('Twig\Compiler')->disableOriginalConstructor()->getMock();
        $compiler->expects($this->exactly(1))
            ->method('write')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('subcompile')
            ->willReturnSelf();
        $compiler->expects($this->exactly(7))
            ->method('raw')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('repr')
            ->willReturnSelf();
        $compiler->expects($this->exactly(0))
            ->method('indent')
            ->willReturnSelf();
        $compiler->expects($this->exactly(0))
            ->method('outdent')
            ->willReturnSelf();
        $return = $instance->compile($compiler);

        $this->assertInternalType(
            'null',
            $return,
            'Return didn\'t match expected type null'
        );
    }
}
