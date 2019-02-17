<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\Token;

/**
 * this is an automatically generated skeleton for testing Token
 * @todo actually test
 **/
class TokenTest extends TestCaseImplementation
{
    /**
     * @return Token
     * @todo make sure the construction works as expected
     **/
    protected function getInstance()
    {
        return new Token(
            null/* @todo unknown, please check */,
            '',
            null/* @todo unknown, please check */,
            null/* @todo unknown, please check */
        );
    }
    
    /**
     * From Token
     * @test
     * @todo replace with actual tests
     * @return void
     **/
    public function testGetPrepend()
    {
        $instance = $this->getInstance();
        $return = $instance->getPrepend();

        $this->assertInternalType(
            'integer',
            $return,
            'Return didn\'t match expected type integer'
        );
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }
}
