<?php

namespace De\Idrinth\TestGenerator\Test\Twig;

use De\Idrinth\TestGenerator\Twig\Lexer;
use De\Idrinth\TestGenerator\Twig\Token;
use PHPUnit\Framework\TestCase as TestCaseImplementation;
use ReflectionClass;
use ReflectionProperty;

class LexerTest extends TestCaseImplementation
{
    /**
     * @return Lexer
     **/
    protected function getInstance()
    {
        $environment = $this->getMockBuilder('Twig_Environment')->disableOriginalClone()->getMock();
        $environment->expects($this->once())
            ->method('getBinaryOperators')
            ->willReturn([]);
        $environment->expects($this->once())
            ->method('getUnaryOperators')
            ->willReturn([]);
        return new Lexer($environment);
    }

    /**
     * @todo add actual tests - this is just temporary
     * @return array
     */
    public function providePushToken()
    {
        return [
            ['why? {{"hi"}} {{ abc }}', \Twig_Token::NAME_TYPE, '', 0, 0],
            ['    why? {{"hi"}} {{ abc }}', \Twig_Token::NAME_TYPE, '', 0, 4],
            [" why?\n   {{ abc }}", \Twig_Token::NAME_TYPE, '', 1, 3]
        ];
    }

    /**
     * @param string $code
     * @param int $type
     * @param mixed $value
     * @param int $line
     * @param int $column
     * @dataProvider providePushToken
     */
    public function testPushToken($code, $type, $value, $line, $column)
    {
        $instance = $this->getInstance();
        $rfClass = new ReflectionClass($instance);
        $this->setInObj($instance, $rfClass->getProperty('code'), $code);
        $this->setInObj($instance, $rfClass->getProperty('lineno'), $line+1);
        $method = $rfClass->getMethod('pushToken');
        $method->setAccessible(true);
        $property = $rfClass->getProperty('tokens');
        $property->setAccessible(true);
        $this->assertNull($property->getValue($instance));
        $method->invoke($instance, $type, $value);
        $return = $this->getInstanceTokenList($instance, $property);
        $this->assertInstanceOf('De\Idrinth\TestGenerator\Twig\Token', $return);
        $this->assertEquals($column, $return->getPrepend());
    }

    /**
     * @param Lexer $instance
     * @param ReflectionProperty $property
     * @param type $value
     */
    private function setInObj(Lexer $instance, ReflectionProperty $property, $value)
    {
        $property->setAccessible(true);
        $property->setValue($instance, $value);
    }

    /**
     * @param Lexer $instance
     * @param ReflectionProperty $property
     * @return Token
     */
    private function getInstanceTokenList(Lexer $instance, ReflectionProperty $property)
    {
        list($return) = $property->getValue($instance);
        return $return;
    }
}
