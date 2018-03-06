<?php

namespace De\Idrinth\TestGenerator\Implementations\Type;

use De\Idrinth\TestGenerator\Interfaces\Type;

abstract class BaseType implements Type
{
    /**
     * @var string
     */
    private $simple;

    /**
     * @var string
     */
    private $class;

    /**
     * @var Type
     */
    private $item;

    /**
     * @var boolean
     */
    private $complex;

    /**
     * @param string $simple
     * @param string $class
     * @param Type $item
     */
    protected function __construct($simple, $class = '', Type $item=null)
    {
        $this->simple = $simple;
        $this->class = $class;
        $this->item = $item;
        $this->complex = ($this->simple === 'object' && $this->class) || ($this->simple === 'array' && $this->item);
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->simple;
    }

    /**
     * @return string
     */
    public function getClassName()
    {
        return $this->class;
    }

    /**
     * @return Type
     */
    public function getItemType()
    {
        return $this->item;
    }

    /**
     * @return boolean
     */
    public function isComplex()
    {
        return $this->complex;
    }
}
