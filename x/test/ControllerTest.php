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
    protected function getInstance()
    {
        return new Controller(
            $this->getMockBuilder('Symfony\Component\Finder\Finder')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\ClassReader')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\ClassWriter')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Model\Composer')->getMock()
        );
    }
    
    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testInit()
    {
        $return = Controller::init();

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Controller',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Controller'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Controller
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testRun()
    {
        $instance = $this->getInstance();
        $return = $instance->run();

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
