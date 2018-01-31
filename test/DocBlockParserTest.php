<?php

namespace De\Idrinth\TestGenerator\Test;

use De\Idrinth\TestGenerator\Implementations\DocBlockParser;
use PHPUnit\Framework\TestCase;

class DocBlockParserTest extends TestCase
{
    private static $case1 = "/**\n* @return string \n*/";
    private static $case2 = "/**\n*@throws \Exception \n"
        . "* @param \$a\n"
        . "*@return string \n"
        . "* @return void\n */";
    private static $case3 = "/**\n * @param int \$z \n"
        . "*@return A|B \n*/";
    private static $case4 = "/**\n*@throws IException \n"
        . "*@throws UException \n"
        . "*@param int \$z\n"
        . "*@param \$m\n"
        . " @return string \n*/";
    private static $case5 = "/**\n*@param boolean \$z\n"
        . "*@param \$m Just a bit of text\n"
        . " @return void \n*/";

    /**
     * @test
     */
    public function testGetReturn()
    {
        $instance = new DocBlockParser();
        $this->assertEquals('null', $instance->getReturn(''));
        $this->assertEquals('string', $instance->getReturn(self::$case1));
        $this->assertEquals('string|void', $instance->getReturn(self::$case2));
        $this->assertEquals('A|B', $instance->getReturn(self::$case3));
        $this->assertEquals('string', $instance->getReturn(self::$case4));
        $this->assertEquals('void', $instance->getReturn(self::$case5));
    }

    /**
     * @test
     */
    public function testGetParams()
    {
        $instance = new DocBlockParser();
        $this->assertCount(0, $instance->getParams(''));
        $this->assertCount(0, $instance->getParams(self::$case1));
        $this->assertCount(1, $instance->getParams(self::$case2));
        $this->assertEquals(array('string'), $instance->getParams(self::$case2));
        $this->assertCount(1, $instance->getParams(self::$case3));
        $this->assertEquals(array('int'), $instance->getParams(self::$case3));
        $this->assertCount(2, $instance->getParams(self::$case4));
        $this->assertEquals(array('int', 'string'), $instance->getParams(self::$case4));
        $this->assertCount(2, $instance->getParams(self::$case5));
        $this->assertEquals(array('boolean', 'string'), $instance->getParams(self::$case5));
    }

    /**
     * @test
     */
    public function testGetExceptions()
    {
        $instance = new DocBlockParser();
        $this->assertCount(0, $instance->getExceptions(''));
        $this->assertCount(0, $instance->getExceptions(self::$case1));
        $this->assertCount(1, $instance->getExceptions(self::$case2));
        $this->assertEquals(array('\Exception'), $instance->getExceptions(self::$case2));
        $this->assertCount(0, $instance->getExceptions(self::$case3));
        $this->assertCount(2, $instance->getExceptions(self::$case4));
        $this->assertEquals(array('IException', 'UException'), $instance->getExceptions(self::$case4));
        $this->assertCount(0, $instance->getExceptions(self::$case5));
    }
}
