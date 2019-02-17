<?php

namespace De\Idrinth\TestGenerator\Model;

class ClassDescriptor
{
    /**
     * @var string
     */
    private $name;

    /**
     *
     * @var string
     */
    private $namespace;

    /**
     * @var MethodDescriptor[]
     */
    private $methods = array();

    /**
     * @var MethodDescriptor
     */
    private $constructor;

    /**
     * @var boolean
     */
    private $abstract;

    /**
     * @var string
     */
    private $extends;
    /**
     * @param string $name
     * @param string $namespace
     * @param MethodDescriptor[] $methods
     * @param MethodDescriptor $constructor
     * @param boolean $abstract
     * @param string $extends
     */
    public function __construct(
        $name,
        $namespace,
        array $methods,
        MethodDescriptor $constructor,
        $abstract,
        $extends = null
    ) {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->methods = $methods;
        $this->constructor = $constructor;
        $this->abstract = $abstract;
        $this->extends = $extends;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @return MethodDescriptor[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return MethodDescriptor
     */
    public function getConstructor()
    {
        return $this->constructor;
    }

    /**
     * @return boolean
     */
    public function isAbstract()
    {
        return $this->abstract;
    }

    /**
     * @return string
     */
    public function getExtends()
    {
        return $this->extends;
    }
}
