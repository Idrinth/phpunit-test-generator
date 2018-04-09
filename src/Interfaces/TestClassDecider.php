<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use InvalidArgumentException;

interface TestClassDecider
{
    /**
     * @param string $constraints
     * @return string
     * @throws InvalidArgumentException
     */
    public function get($constraints);
}