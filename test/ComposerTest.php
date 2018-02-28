<?php

namespace De\Idrinth\TestGenerator\Test;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\Composer;
use SplFileInfo;

class ComposerTest extends TestCaseImplementation
{
    /**
     * @param string $file
     * @return Composer
     **/
    protected function getInstance($file)
    {
        return new Composer(new SplFileInfo($file));
    }

    /**
     * From Composer
     * @test
     * @todo replace with actual tests
     **/
    public function testGetProductionNamespacesToFolders ()
    {
        $instance = $this->getInstance(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json');
    
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * From Composer
     * @test
     * @todo replace with actual tests
     **/
    public function testGetDevelopmentNamespacesToFolders ()
    {
        $instance = $this->getInstance(dirname(__DIR__).DIRECTORY_SEPARATOR.'composer.json');
    
        $this->assertInternalType(
            'array',
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
