<?php

namespace De\Idrinth\TestGenerator\Twig;

use De\Idrinth\TestGenerator\Model\Composer;
use De\Idrinth\TestGenerator\Twig\IncludeParser;
use De\Idrinth\TestGenerator\Twig\Lexer;
use SplFileInfo;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Renderer extends Environment
{
    /**
     * @var string
     */
    private $testClass;

    /**
     * @param SplFileInfo $templates
     * @param Composer $composer
     */
    public function __construct(SplFileInfo $templates, Composer $composer)
    {
        parent::__construct(new FilesystemLoader($templates->getPathname()));
        $this->addTokenParser(new IncludeParser());
        $this->filterToUpperCamelCase();
        $this->setLexer(new Lexer($this));
        $this->testClass = $composer->getTestClass();
    }

    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render($name, array $context = array())
    {
        $context['config']['testcase'] = $this->testClass;
        return preg_replace('/ +($\n)/', '$1', parent::render($name, $context));
    }

    /**
     * adds to upper camel case filter
     * @return void
     */
    private function filterToUpperCamelCase()
    {
        $this->addFilter(new TwigFilter(
            'toUpperCamelCase',
            function ($string) {
                $result = '';
                foreach (explode(
                    '_',
                    trim(preg_replace('/[^_A-Za-z0-9]+/', '_', $string), '_')
                ) as $part) {
                    $result.= strtoupper($part{0});
                    if (strlen($part)>1) {
                        $result.= substr($part, 1);
                    }
                }
                return $result;
            }
        ));
    }
}
