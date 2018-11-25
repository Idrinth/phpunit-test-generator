<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator\Test\Twig;

use PHPUnit\Framework\TestCase as TestCaseImplementation;
use De\Idrinth\TestGenerator\Twig\Token;

class TokenTest extends TestCaseImplementation
{
    /**
     * @return Token
     **/
    protected function getInstance(): Token
    {
        return new Token(
            0,
            '',
            0,
            17
        );
    }

    /**
     * From Token
     * @test
     * @return void
     **/
    public function testGetPrepend(): void
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
