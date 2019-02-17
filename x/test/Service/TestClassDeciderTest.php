<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\TestClassDecider;

/**
 * this is an automatically generated skeleton for testing TestClassDecider
 * @todo actually test
 **/
class TestClassDeciderTest extends TestCaseImplementation
{
    /**
     * @return TestClassDecider
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new TestClassDecider();
    }
    
    /**
     * From TestClassDecider
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
     * From TestClassDecider
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
}
