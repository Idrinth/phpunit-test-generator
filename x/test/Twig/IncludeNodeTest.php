<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\IncludeNode;

/**
 * this is an automatically generated skeleton for testing IncludeNode
 * @todo actually test
 **/
class IncludeNodeTest extends TestCaseImplementation
{
    /**
     * @return IncludeNode
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new IncludeNode(
            $this->getMockBuilder('Twig_Node_Expression')->getMock(),
            $this->getMockBuilder('Twig_Node_Expression')->getMock(),
            null/* @todo unknown, please check */,
            null/* @todo unknown, please check */,
            null/* @todo unknown, please check */,
            null/* @todo unknown, please check */
        );
    }
    
    /**
     * From IncludeNode
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testCompile()
    {
        $instance = $this->getInstance();
        $return = $instance->compile(
            $this->getMockBuilder('Twig\Compiler')->getMock()
        );

        $this->assertInternalType(
            'null',
            $return,
            'Return didn\'t match expected type null'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
