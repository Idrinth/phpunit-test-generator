<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Lexer as TL;
use Twig_Token;

final class Lexer extends TL
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
    private function getOriginalPos(): int
    {
        $lines = explode("\n", $this->code);
        if (!isset($lines[$this->lineno-1]) || empty($lines[$this->lineno-1])) {
            return 0;
        }
        return strlen(preg_replace('/^( *).*?$/', '$1', $lines[$this->lineno-1]));
    }
}
