<?php

namespace De\Idrinth\TestGenerator\Test\Factory;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Factory\MethodFactory;

/**
 * this is an automatically generated skeleton for testing MethodFactory
 * @todo actually test
 **/
class MethodFactoryTest extends TestCaseImplementation
{
    /**
     * @return MethodFactory
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new MethodFactory(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\DocBlockParser')->getMock()
        );
    }
    
    /**
     * From MethodFactory
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testCreate()
    {
        $instance = $this->getInstance();
        $return = $instance->create(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\TypeResolver')->getMock(),
            $this->getMockBuilder('PhpParser\Node\Stmt\ClassMethod')->getMock()
        );

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Model\MethodDescriptor',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\MethodDescriptor'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
