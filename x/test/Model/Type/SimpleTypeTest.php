<?php

namespace De\Idrinth\TestGenerator\Test\Model\Type;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Model\Type\SimpleType;

/**
 * this is an automatically generated skeleton for testing SimpleType
 * @todo actually test
 **/
class SimpleTypeTest extends TestCaseImplementation
{
    /**
     * @return SimpleType
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new SimpleType(
            ''
        );
    }
    
    /**
     * From BaseType
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetType()
    {
        $instance = $this->getInstance();
        $return = $instance->getType();

        $this->assertInternalType(
            'string',
            $return,
            'Return didn\'t match expected type string'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From BaseType
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetClassName()
    {
        $instance = $this->getInstance();
        $return = $instance->getClassName();

        $this->assertInternalType(
            'string',
            $return,
            'Return didn\'t match expected type string'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From BaseType
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetItemType()
    {
        $instance = $this->getInstance();
        $return = $instance->getItemType();

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
     * From BaseType
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testIsComplex()
    {
        $instance = $this->getInstance();
        $return = $instance->isComplex();

        $this->assertInternalType(
            'boolean',
            $return,
            'Return didn\'t match expected type boolean'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
