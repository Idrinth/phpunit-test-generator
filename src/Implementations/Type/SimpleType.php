<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;

use De\Idrinth\TestGenerator\Interfaces\Type;

class SimpleType implements Type
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param string $type
     */
    public function __construct($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }
}