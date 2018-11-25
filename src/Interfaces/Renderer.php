<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface Renderer
{
    /**
     * @param string $name
     * @param array $context
     * @return string
     */
    public function render($name, array $context = []);
}
