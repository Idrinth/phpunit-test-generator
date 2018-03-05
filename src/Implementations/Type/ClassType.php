<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;

use De\Idrinth\TestGenerator\Interfaces\ClassType as CTI;

class ClassType implements CTI
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
        return 'object';
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->type;
    }
}
