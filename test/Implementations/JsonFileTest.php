<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Implementations\JsonFile;

class JsonFileTest extends TestCaseImplementation
{
    /**
     * @test
     */
    public function testExistingFile()
    {
        $base = dirname(dirname(__DIR__));
        $file = new JsonFile($base.DIRECTORY_SEPARATOR.'composer.json');
        $this->assertEquals($base, $file->getPath());
        $this->assertArrayHasKey('name', $file->getContent());
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /File .+ doesn't exist or isn't readable\./
     */
    public function testMissingFile()
    {
        new JsonFile(__DIR__.DIRECTORY_SEPARATOR.'composer.miss.json');
    }

    /**
     * @test
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessageRegExp /File .+ couldn't be parsed as json\./
     */
    public function testNoneJsonFile()
    {
        new JsonFile(__FILE__);
    }
}
