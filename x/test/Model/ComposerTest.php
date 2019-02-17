<?php

namespace De\Idrinth\TestGenerator\Test\Model;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Model\Composer;

/**
 * this is an automatically generated skeleton for testing Composer
 * @todo actually test
 **/
class ComposerTest extends TestCaseImplementation
{
    /**
     * @return Composer
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new Composer(
            $this->getMockBuilder('De\Idrinth\TestGenerator\File\JsonFile')->getMock(),
            $this->getMockBuilder('De\Idrinth\TestGenerator\Service\ComposerProcessor')->getMock()
        );
    }
    
    /**
     * From Composer
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetTestClass()
    {
        $instance = $this->getInstance();
        $return = $instance->getTestClass();

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
     * From Composer
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetProductionNamespacesToFolders()
    {
        $instance = $this->getInstance();
        $return = $instance->getProductionNamespacesToFolders();

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
     * From Composer
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetDevelopmentNamespacesToFolders()
    {
        $instance = $this->getInstance();
        $return = $instance->getDevelopmentNamespacesToFolders();

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
