<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator\Twig;

use Twig\TokenParser\IncludeTokenParser;
use Twig_Token;

final class IncludeParser extends IncludeTokenParser
{
    /**
     * @param Token $token
     * @return IncludeNode
     */
    public function parse(Twig_Token $token)
    {
        $expr = $this->parser->getExpressionParser()->parseExpression();
        list($variables, $only, $ignoreMissing) = $this->parseArguments();
        return new IncludeNode(
            $expr,
            $variables,
            $only,
            $ignoreMissing,
            $token->getLine(),
            $token->getPrepend()
        );
    }
}
