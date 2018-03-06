<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\ClassType;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor as MDI;
use De\Idrinth\TestGenerator\Interfaces\Type;

class MethodDescriptor implements MDI
{
    private $name;
    private $params = array();
    private $return;
    private $exceptions = array();

    /**
     * @param string $name
     * @param Type[] $params
     * @param Type $return
     * @param ClassType[] $exceptions
     */
    public function __construct($name, $params, Type $return, $exceptions = array())
    {
        $this->name = $name;
        $this->params = $params;
        $this->return = $return;
        $this->exceptions = $exceptions;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return Type[]
     */
    public function getParams()
    {
        return $this->params;
    }

    /**
     * @return Type
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @return ClassType[]
     */
    public function getExceptions()
    {
        return $this->exceptions;
    }
}
