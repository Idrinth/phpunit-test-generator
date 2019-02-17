<?php

namespace De\Idrinth\TestGenerator\Test;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Container;

/**
 * this is an automatically generated skeleton for testing Container
 * @todo actually test
 **/
class ContainerTest extends TestCaseImplementation
{
    /**
     * @return Container
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new Container();
    }
    
    /**
     * From Container
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGet()
    {
        $instance = $this->getInstance();
        $return = $instance->get(
            ''
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Container
     * @test
     * @todo replace with actual tests
     * @expectedException \InvalidArgumentException
     * @return void
     **/
    public function testGetThrowsInvalidArgumentException()
    {
        $this->getInstance()->get(
            ''
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Container
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testHas()
    {
        $instance = $this->getInstance();
        $return = $instance->has(
            ''
        );

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
     * From Container
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testAddValue()
    {
        $instance = $this->getInstance();
        $return = $instance->addValue(
            '',
            null/* @todo unknown, please check */
        );

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
     * From Container
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testCreate()
    {
        $return = Container::create();

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Container',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Container'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
