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
    private $colnum;

    /**
     * @param Twig_Node_Expression $expr
     * @param Twig_Node_Expression $variables
     * @param boolean $only
     * @param boolean $ignoreMissing
     * @param int $lineno
     * @param int $colnum
     */
    public function __construct(
        Twig_Node_Expression $expr,
        Twig_Node_Expression $variables = null,
        $only = false,
        $ignoreMissing = false,
        $lineno = 0,
        $colnum = 0
    ) {
        parent::__construct($expr, $variables, $only, $ignoreMissing, $lineno, 'include');
        $this->colnum = $colnum;
    }

    /**
     * @param Compiler $compiler
     */
    public function compile(Compiler $compiler)
    {
        $compiler->addDebugInfo($this);
        if($this->colnum) {
            $compiler->raw("ob_start();\n");
        }
        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->write("try {\n")
                ->indent();
        }
        $this->addGetTemplate($compiler);
        $compiler->raw('->display(');
        $this->addTemplateArguments($compiler);
        $compiler->raw(");\n");
        if ($this->getAttribute('ignore_missing')) {
            $compiler
                ->outdent()
                ->write("} catch (Twig_Error_Loader \$e) {\n")
                ->indent()
                ->write("// ignore missing template\n")
                ->outdent()
                ->write("}\n\n");
        }
        if($this->colnum) {
            $compiler->raw("echo preg_replace(\n")
                ->raw("'/\n\s+($|\n)/',\n")
                ->raw("\"\\n\\$1\",\n")
                ->raw("str_replace(\n")
                ->indent()
                ->raw("\"\\n\",")
                ->raw("\"\\n\".")
                ->raw("str_repeat(' ', {$this->colnum}),\n")
                ->raw("ob_get_clean()")
                ->outdent()
                ->raw(")")
                ->raw(");\n\n");
        }
    }
}
