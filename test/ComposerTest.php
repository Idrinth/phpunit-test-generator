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
     **/
    public function testGetProductionNamespacesToFolders()
    {
        $base = dirname(__DIR__).DIRECTORY_SEPARATOR;
        $instance = $this->getInstance($base.'composer.json');
        $this->assertInternalType(
            'array',
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            array("De\\Idrinth\\TestGenerator" => "{$base}src"),
            $instance->getProductionNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
    }

    /**
     * From Composer
     * @test
     **/
    public function testGetDevelopmentNamespacesToFolders()
    {
        $base = dirname(__DIR__).DIRECTORY_SEPARATOR;
        $instance = $this->getInstance($base.'composer.json');
        $this->assertInternalType(
            'array',
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected type array'
        );
        $this->assertEquals(
            array("De\\Idrinth\\TestGenerator\\Test" => "{$base}test"),
            $instance->getDevelopmentNamespacesToFolders(),
            'Return didn\'t match expected array'
        );
    }
}
