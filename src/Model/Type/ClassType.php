<?php

namespace De\Idrinth\TestGenerator\Model\Type;

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
