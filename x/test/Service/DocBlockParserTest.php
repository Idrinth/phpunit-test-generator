<?php

namespace De\Idrinth\TestGenerator\Test\Service;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Service\DocBlockParser;

/**
 * this is an automatically generated skeleton for testing DocBlockParser
 * @todo actually test
 **/
class DocBlockParserTest extends TestCaseImplementation
{
    /**
     * @return DocBlockParser
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new DocBlockParser();
    }
    
    /**
     * From DocBlockParser
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetReturn()
    {
        $instance = $this->getInstance();
        $return = $instance->getReturn(
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
     * From DocBlockParser
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetParams()
    {
        $instance = $this->getInstance();
        $return = $instance->getParams(
            ''
        );

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        foreach($return as $return_) {

            $this->assertInternalType(
                'string',
                $return_,
                'Return didn\'t match expected type string'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From DocBlockParser
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetExceptions()
    {
        $instance = $this->getInstance();
        $return = $instance->getExceptions(
            ''
        );

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        foreach($return as $return_) {

            $this->assertInternalType(
                'string',
                $return_,
                'Return didn\'t match expected type string'
            );}
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
