<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\ComposerProcessor;

/**
 * this is an automatically generated skeleton for testing ComposerProcessor
 * @todo actually test
 **/
class ComposerProcessorTest extends TestCaseImplementation
{
    /**
     * @return ComposerProcessor
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new ComposerProcessor(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\TestClassDecider')->getMock(),
            ''
        );
    }
    
    /**
     * From ComposerProcessor
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testProcess()
    {
        $instance = $this->getInstance();
        $return = $instance->process(
            array(),
            ''
        );

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From ComposerProcessor
     * @test
     * @todo replace with actual tests
     * @expectedException \InvalidArgumentException
     * @return void
     **/
    public function testProcessThrowsInvalidArgumentException()
    {
        $this->getInstance()->process(
            array(),
            ''
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
