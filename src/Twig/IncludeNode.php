<?php

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Compiler;
use Twig\Node\IncludeNode as TIN;
use Twig_Node_Expression;

class IncludeNode extends TIN
{
    /**
     * @var int
     */
    private $prepend;

    /**
     * @param Twig_Node_Expression $expr
     * @param Twig_Node_Expression $variables
     * @param boolean $only
     * @param boolean $ignoreMissing
     * @param int $lineno
     * @param int $prepend
     */
    public function __construct(
        Twig_Node_Expression $expr,
        Twig_Node_Expression $variables = null,
        $only = false,
        $ignoreMissing = false,
        $lineno = 0,
        $prepend = 0
    ) {
        parent::__construct($expr, $variables, $only, $ignoreMissing, $lineno, 'include');
        $this->prepend = $prepend;
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        if ($this->prepend) {
            return parent::compile($compiler);
        }
        $compiler->raw("ob_start();\n");
        parent::compile($compiler);
        $compiler->raw("echo preg_replace(\n")
            ->raw("'/\n\s+($|\n)/',\n")
            ->raw("\"\\n\\$1\",\n")
            ->raw("str_replace(\n")
            ->indent()
            ->raw("\"\\n\",")
            ->raw("\"\\n\".")
            ->raw("str_repeat(' ', {$this->prepend}),\n")
            ->raw("ob_get_clean()")
            ->outdent()
            ->raw(")")
            ->raw(");\n\n");
    }
}
