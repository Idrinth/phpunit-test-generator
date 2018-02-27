<?php

namespace De\Idrinth\TestGenerator\Test;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Controller;

/**
 * this is an automatically generated skeleton for testing Controller
 * @todo actually test
 **/
class ControllerTest extends TestCaseImplementation
{
    /**
     * @return Controller
     * @todo make sure the construction works as expected
     **/
    protected function getInstance ()
    {
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
        return new Controller(null, null, null, null, null);
    }

    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     **/
    public function testInit ()
    {
        $instance = $this->getInstance();
    
        $this->assertInternalType(
            'null',
            $instance->init(),
            'Return didn\'t match expected type null'
        );
        
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     **/
    public function testRun ()
    {
        $instance = $this->getInstance();
    
        $this->assertInternalType(
            'null',
            $instance->run(),
            'Return didn\'t match expected type null'
        );
        
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
