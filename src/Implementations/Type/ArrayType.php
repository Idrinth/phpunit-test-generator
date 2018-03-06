<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;

use De\Idrinth\TestGenerator\Interfaces\Type;

class ArrayType extends BaseType
{
    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        parent::__construct('array', null, $type);
    }
}
