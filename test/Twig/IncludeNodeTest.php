<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\IncludeNode;

class IncludeNodeTest extends TestCaseImplementation
{
    /**
     * @return IncludeNode
     **/
    protected function getInstance()
    {
        return new IncludeNode(
            $this->getMockBuilder('Twig_Node_Expression')->getMock(),
            $this->getMockBuilder('Twig_Node_Expression')->getMock()
        );
    }

    /**
     * From IncludeNode
     * @test
     **/
    public function testCompile()
    {
        $instance = $this->getInstance();
        $compiler = $this->getMockBuilder('Twig\Compiler')->disableOriginalConstructor()->getMock();
        $compiler->expects($this->exactly(1))
            ->method('write')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('subcompile')
            ->willReturnSelf();
        $compiler->expects($this->exactly(18))
            ->method('raw')
            ->willReturnSelf();
        $compiler->expects($this->exactly(2))
            ->method('repr')
            ->willReturnSelf();
        $compiler->expects($this->exactly(1))
            ->method('indent')
            ->willReturnSelf();
        $compiler->expects($this->exactly(1))
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
