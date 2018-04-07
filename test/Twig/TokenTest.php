<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\Token;

class TokenTest extends TestCaseImplementation
{
    /**
     * @return Token
     **/
    protected function getInstance()
    {
        return new Token(
            null,
            '',
            null,
            17
        );
    }

    /**
     * From Token
     * @test
     **/
    public function testGetPrepend()
    {
        $instance = $this->getInstance();
        $return = $instance->getPrepend();
        $this->assertInternalType(
            'int',
            $return,
            'Return didn\'t match expected int'
        );
        $this->assertEquals(17, $return);
    }
}
