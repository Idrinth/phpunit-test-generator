<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface ComposerProcessor
{
    /**
     * @param array $composer
     * @param string $path
     * @return array [$autoloadProd, $autoloadDev, $testClass]
     * @throws InvalidArgumentException if file is unusable
     */
    public function process(array $composer, $path);
}
