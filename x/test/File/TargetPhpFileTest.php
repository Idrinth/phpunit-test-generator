<?php

namespace De\Idrinth\TestGenerator\Test\File;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\File\TargetPhpFile;

/**
 * this is an automatically generated skeleton for testing TargetPhpFile
 * @todo actually test
 **/
class TargetPhpFileTest extends TestCaseImplementation
{
    /**
     * @return TargetPhpFile
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new TargetPhpFile(
            null/* @todo unknown, please check */,
            ''
        );
    }
    
    /**
     * From TargetPhpFile
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testMayWrite()
    {
        $instance = $this->getInstance();
        $return = $instance->mayWrite();

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
     * From TargetPhpFile
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testWrite()
    {
        $instance = $this->getInstance();
        $return = $instance->write(
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
     * From TargetPhpFile
     * @test
     * @todo replace with actual tests
     * @expectedException \De\Idrinth\TestGenerator\File\RuntimeException
     * @return void
     **/
    public function testWriteThrowsDeIdrinthTestGeneratorFileRuntimeException()
    {
        $this->getInstance()->write(
            ''
        );

        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
