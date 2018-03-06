<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;


class ClassType extends BaseType
{
    /**
     * @param string $type
     */
    public function __construct($type)
    {
        parent::__construct('object', $type);
    }

}
