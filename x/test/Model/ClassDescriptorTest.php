<?php

namespace De\Idrinth\TestGenerator\Test\Model;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Model\ClassDescriptor;

/**
 * this is an automatically generated skeleton for testing ClassDescriptor
 * @todo actually test
 **/
class ClassDescriptorTest extends TestCaseImplementation
{
    /**
     * @return ClassDescriptor
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new ClassDescriptor(
            '',
            '',
            array(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\MethodDescriptor')->getMock(),
            null/* @todo unknown, please check */,
            ''
        );
    }
    
    /**
     * From ClassDescriptor
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
     * From ClassDescriptor
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

    /**
     * From ClassDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetMethods()
    {
        $instance = $this->getInstance();
        $return = $instance->getMethods();

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
                'De\Idrinth\TestGenerator\Model\MethodDescriptor',
                $return_,
                'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\MethodDescriptor'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From ClassDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetConstructor()
    {
        $instance = $this->getInstance();
        $return = $instance->getConstructor();

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

    /**
     * From ClassDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testIsAbstract()
    {
        $instance = $this->getInstance();
        $return = $instance->isAbstract();

        $this->assertInternalType(
            'boolean',
            $return,
            'Return didn\'t match expected type boolean'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From ClassDescriptor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetExtends()
    {
        $instance = $this->getInstance();
        $return = $instance->getExtends();

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
