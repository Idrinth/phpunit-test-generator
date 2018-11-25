<?php declare (strict_types=1);

namespace De\Idrinth\TestGenerator\Twig;

use Twig\Compiler;
use Twig\Node\IncludeNode as TIN;
use Twig_Node_Expression;

final class IncludeNode extends TIN
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
        bool $only = false,
        bool $ignoreMissing = false,
        int $lineno = 0,
        int $prepend = 0
    ) {
        parent::__construct($expr, $variables, $only, $ignoreMissing, $lineno, 'include');
        $this->prepend = $prepend;
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler): void
    {
        if (!$this->prepend) {
            parent::compile($compiler);
            return;
        }
        $compiler->raw("ob_start();\n");
        parent::compile($compiler);
        $compiler->raw("if (\$internal = ob_get_clean()) {\n")
            ->indent()
            ->raw("echo preg_replace(\n")
            ->indent()
            ->raw("'/\\n\s+($|\\n)/',\n")
            ->raw("\"\\n\$1\",\n")
            ->raw("str_replace(\n")
            ->indent()
            ->raw("\"\\n\",")
            ->raw("\"\\n\".")
            ->raw("str_repeat(' ', {$this->prepend}),\n")
            ->raw("\$internal\n")
            ->outdent()
            ->raw(")")
            ->outdent()
            ->raw(");")
            ->outdent()
            ->raw("}")
            ->raw("\n\n");
    }
}
