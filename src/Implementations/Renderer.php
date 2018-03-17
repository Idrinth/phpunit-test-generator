<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\Renderer as RI;
use SplFileInfo;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use Twig\TwigFilter;

class Renderer extends Environment implements RI
{
    /**
     * @param SplFileInfo $templates
     */
    public function __construct(SplFileInfo $templates)
    {
        parent::__construct(new FilesystemLoader($templates->getPathname()));
        $this->filterToUpperCamelCase();
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
