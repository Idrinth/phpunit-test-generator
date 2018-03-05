<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface Type
{
    /**
     * Returns a simple type, that can be used for is_{type} checks
     * @return string
     */
    public function getType();
}
