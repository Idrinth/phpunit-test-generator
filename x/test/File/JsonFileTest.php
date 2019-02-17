<?php

namespace De\Idrinth\TestGenerator\Test\File;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\File\JsonFile;

/**
 * this is an automatically generated skeleton for testing JsonFile
 * @todo actually test
 **/
class JsonFileTest extends TestCaseImplementation
{
    /**
     * @return JsonFile
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new JsonFile(
            null/* @todo unknown, please check */
        );
    }
    
    /**
     * From JsonFile
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetPath()
    {
        $instance = $this->getInstance();
        $return = $instance->getPath();

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
     * From JsonFile
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetContent()
    {
        $instance = $this->getInstance();
        $return = $instance->getContent();

        $this->assertInternalType(
            'array',
            $return,
            'Return didn\'t match expected type array'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
