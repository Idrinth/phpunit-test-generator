<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface DocBlockParser
{
    /**
     * @param string $rawDocBlock
     * @return string
     */
    public function getReturn($rawDocBlock);

    /**
     * @param string $rawDocBlock
     * @return string[]
     */
    public function getParams($rawDocBlock);

    /**
     * @param string $rawDocBlock
     * @return string[]
     */
    public function getExceptions($rawDocBlock);
}
