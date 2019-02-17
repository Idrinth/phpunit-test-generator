<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\IncludeParser;

/**
 * this is an automatically generated skeleton for testing IncludeParser
 * @todo actually test
 **/
class IncludeParserTest extends TestCaseImplementation
{
    /**
     * @return IncludeParser
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new IncludeParser();
    }
    
    /**
     * From IncludeParser
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testParse()
    {
        $instance = $this->getInstance();
        $return = $instance->parse(
            $this->getMockBuilder('Twig_Token')->getMock()
        );

        $this->assertInternalType(
            'object',
            $return,
            'Return didn\'t match expected type object'
        );
        $this->assertInstanceOf(
            'De\Idrinth\TestGenerator\Twig\IncludeNode',
            $return,
            'Return didn\'t match expected instance De\Idrinth\TestGenerator\Twig\IncludeNode'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
