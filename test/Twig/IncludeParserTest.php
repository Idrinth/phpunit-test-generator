<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\IncludeParser;

class IncludeParserTest extends TestCaseImplementation
{
    /**
     * @return IncludeParser
     **/
    protected function getInstance()
    {
        return new IncludeParser();
    }

    /**
     * From IncludeParser
     * @test
     **/
    public function testParse ()
    {
        $instance = $this->getInstance();
        $parser = $this->getMockBuilder('Twig_Parser')->disableOriginalConstructor()->getMock();
        $expParser = $this->getMockBuilder('Twig_ExpressionParser')->disableOriginalConstructor()->getMock();
        $expParser->expects($this->once())
            ->method('parseExpression')
            ->willReturn($this->getMockBuilder('Twig_Node_Expression')->disableOriginalConstructor()->getMock());
        $parser->expects($this->once())
            ->method('getExpressionParser')
            ->willReturn($expParser);
        $parser->expects($this->once())
            ->method('getStream')
            ->willReturn($this->getMockBuilder('Twig_TokenStream')->disableOriginalConstructor()->getMock());
        $instance->setParser($parser);
        $return = $instance->parse(
            $this->getMockBuilder('De\Idrinth\TestGenerator\Twig\Token')->disableOriginalConstructor()->getMock()
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
    }
}
