<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;

use De\Idrinth\TestGenerator\Interfaces\ArrayType as ATI;
use De\Idrinth\TestGenerator\Interfaces\Type;

class ArrayType implements ATI
{
    /**
     * @var string
     */
    private $type;

    /**
     * @param Type $type
     */
    public function __construct(Type $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return 'array';
    }

    /**
     * @return Type
     */
    public function getItemType()
    {
        return $this->type;
    }
}
