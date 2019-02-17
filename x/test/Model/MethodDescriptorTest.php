<?php

namespace De\Idrinth\TestGenerator\Test\Model;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Model\MethodDescriptor;

/**
 * this is an automatically generated skeleton for testing MethodDescriptor
 * @todo actually test
 **/
class MethodDescriptorTest extends TestCaseImplementation
{
    /**
     * @return MethodDescriptor
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new MethodDescriptor(
            '',
            array(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\Type')->getMock(),
            null/* @todo unknown, please check */,
            array()
        );
    }
    
    /**
     * From MethodDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetName()
    {
        $instance = $this->getInstance();
        $return = $instance->getName();

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
     * From MethodDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetParams()
    {
        $instance = $this->getInstance();
        $return = $instance->getParams();

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        foreach($return as $return_) {

            $this->assertInternalType(
                'object',
                $return_,
                'Return didn\'t match expected type object'
            );
            $this->assertInstanceOf(
                'De\Idrinth\TestGenerator\Model\Type',
                $return_,
                'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\Type'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From MethodDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetReturn()
    {
        $instance = $this->getInstance();
        $return = $instance->getReturn();

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
     * From MethodDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetExceptions()
    {
        $instance = $this->getInstance();
        $return = $instance->getExceptions();

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        foreach($return as $return_) {

            $this->assertInternalType(
                'object',
                $return_,
                'Return didn\'t match expected type object'
            );
            $this->assertInstanceOf(
                'De\Idrinth\TestGenerator\Model\Type\ClassType',
                $return_,
                'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\Type\ClassType'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From MethodDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testIsStatic()
    {
        $instance = $this->getInstance();
        $return = $instance->isStatic();

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
