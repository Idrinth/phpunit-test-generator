<?php

namespace De\Idrinth\TestGenerator\Model;

use De\Idrinth\TestGenerator\Model\Type\ClassType;

class MethodDescriptor
{
    /**
     * @var string
     */
    private $name;

    /**
     * @var Type[]
     */
    private $params = array();

    /**
     * @var Type
     */
    private $return;

    /**
     * @var boolean
     */
    private $static;

    /**
     * @var Type[]
     */
    private $exceptions = array();

    /**
     * @param string $name
     * @param Type[] $params
     * @param Type $return
     * @param boolean $static
     * @param ClassType[] $exceptions
     */
    public function __construct($name, $params, Type $return, $static = false, $exceptions = array())
    {
        $this->name = $name;
        $this->params = $params;
        $this->return = $return;
        $this->exceptions = $exceptions;
        $this->static = $static;
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

    /**
     * @return boolean
     */
    public function isStatic()
    {
        return $this->static;
    }
}
