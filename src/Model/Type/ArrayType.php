<?php

namespace De\Idrinth\TestGenerator\Model\Type;

use De\Idrinth\TestGenerator\Model\Type;

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
