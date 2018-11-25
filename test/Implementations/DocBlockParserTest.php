<?php

namespace De\Idrinth\TestGenerator\Test\Implementations;

use De\Idrinth\TestGenerator\Implementations\DocBlockParser;
use PHPUnit\Framework\TestCase;

class DocBlockParserTest extends TestCase
{
    /**
     * @return string[]
     */
    private function getCases()
    {
        return [
            '',
            "/**\n* @return string \n*/",
            "/**\n*@throws \Exception \n* @param \$a\n*@return string \n* @return void\n */",
            "/**\n * @param int \$z \n*@return A|B \n*/",
            "/**\n*@throws IException \n*@throws UException \n*@param int \$z\n*@param \$m\n @return string \n*/",
            "/**\n*@param boolean \$z\n*@param \$m Just a bit of text\n @return  \n*/"
        ];
    }

    /**
     * @test
     */
    public function testGetReturn()
    {
        $cases = $this->getCases();
        $instance = new DocBlockParser();
        $this->assertEquals('null', $instance->getReturn($cases[0]));
        $this->assertEquals('string', $instance->getReturn($cases[1]));
        $this->assertEquals('string|void', $instance->getReturn($cases[2]));
        $this->assertEquals('A|B', $instance->getReturn($cases[3]));
        $this->assertEquals('string', $instance->getReturn($cases[4]));
        $this->assertEquals('null', $instance->getReturn($cases[5]));
    }

    /**
     * @test
     */
    public function testGetParams()
    {
        $cases = $this->getCases();
        $instance = new DocBlockParser();
        $this->assertCount(0, $instance->getParams(''));
        $this->assertCount(0, $instance->getParams($cases[1]));
        $this->assertCount(1, $instance->getParams($cases[2]));
        $this->assertEquals(['string'], $instance->getParams($cases[2]));
        $this->assertCount(1, $instance->getParams($cases[3]));
        $this->assertEquals(['int'], $instance->getParams($cases[3]));
        $this->assertCount(2, $instance->getParams($cases[4]));
        $this->assertEquals(['int', 'string'], $instance->getParams($cases[4]));
        $this->assertCount(2, $instance->getParams($cases[5]));
        $this->assertEquals(['boolean', 'string'], $instance->getParams($cases[5]));
    }

    /**
     * @test
     */
    public function testGetExceptions()
    {
        $cases = $this->getCases();
        $instance = new DocBlockParser();
        $this->assertCount(0, $instance->getExceptions(''));
        $this->assertCount(0, $instance->getExceptions($cases[1]));
        $this->assertCount(1, $instance->getExceptions($cases[2]));
        $this->assertEquals(['\Exception'], $instance->getExceptions($cases[2]));
        $this->assertCount(0, $instance->getExceptions($cases[3]));
        $this->assertCount(2, $instance->getExceptions($cases[4]));
        $this->assertEquals(['IException', 'UException'], $instance->getExceptions($cases[4]));
        $this->assertCount(0, $instance->getExceptions($cases[5]));
    }
}
