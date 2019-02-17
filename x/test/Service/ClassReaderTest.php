<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\ClassReader;

/**
 * this is an automatically generated skeleton for testing ClassReader
 * @todo actually test
 **/
class ClassReaderTest extends TestCaseImplementation
{
    /**
     * @return ClassReader
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new ClassReader(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Factory\ClassDescriptorFactory')->getMock(),
            $this->getMockBuilder('PhpParser\Parser')->getMock()
        );
    }
    
    /**
     * From ClassReader
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testParse()
    {
        $instance = $this->getInstance();
        $return = $instance->parse(
            $this->getMockBuilder('SplFileInfo')->getMock()
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
     * From ClassReader
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetResults()
    {
        $instance = $this->getInstance();
        $return = $instance->getResults();

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
                'De\Idrinth\TestGenerator\Model\ClassDescriptor',
                $return_,
                'Return didn\'t match expected instance De\Idrinth\TestGenerator\Model\ClassDescriptor'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
