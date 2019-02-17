<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\TypeResolver;

/**
 * this is an automatically generated skeleton for testing TypeResolver
 * @todo actually test
 **/
class TypeResolverTest extends TestCaseImplementation
{
    /**
     * @return TypeResolver
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new TypeResolver(
            $this->getMockBuilder('PhpParser\Node\Stmt\Namespace_')->getMock()
        );
    }
    
    /**
     * From TypeResolver
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testAddUse()
    {
        $instance = $this->getInstance();
        $return = $instance->addUse(
            $this->getMockBuilder('PhpParser\Node\Stmt\Use_')->getMock()
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

    /**
     * From TypeResolver
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testToType()
    {
        $instance = $this->getInstance();
        $return = $instance->toType(
            null/* @todo unknown, please check */,
            ''
        );

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Model\Type',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\Type'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From TypeResolver
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetNamespace()
    {
        $instance = $this->getInstance();
        $return = $instance->getNamespace();

        $this->assertInternalType(
            'string',
            $return,
            'Return didn\'t match expected type string'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
