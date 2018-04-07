<?php

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Lexer as TL;
use Twig_Token;

class Lexer extends TL
{
    /**
     * @param int $type
     * @param mixed $value
     * @return void
     */
    protected function pushToken($type, $value = '')
    {
        // do not push empty text tokens
        if (Twig_Token::TEXT_TYPE === $type && '' === $value) {
            return;
        }
        $this->tokens[] = new Token($type, $value, $this->lineno, $this->getOriginalPos());
    }

    /**
     * tries to determine the position of the first none-space character
     * @return int
     */
    private function getOriginalPos()
    {
        $lines = explode("\n", $this->code);
        if (!isset($lines[$this->lineno]) || empty($lines[$this->lineno])) {
            return 0;
        }
        $length = strlen(preg_replace('/^( *).*$/', '$1', $lines[$this->lineno]));
        return $lines[$this->lineno][$length] === '{'?$length:$length+4;
    }
}
