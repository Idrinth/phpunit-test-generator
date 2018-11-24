<?php

namespace De\Idrinth\TestGenerator\Implementations;

class ClassDescriptor implements \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor
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
     * @var \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor[]
     */
    private $methods = [];

    /**
     * @var \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor
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
     * @param \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor[] $methods
     * @param \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor $constructor
     * @param boolean $abstract
     * @param string $extends
     */
    public function __construct(
        $name,
        $namespace,
        array $methods,
        \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor $constructor,
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
     * @return \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor[]
     */
    public function getMethods()
    {
        return $this->methods;
    }

    /**
     * @return \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor
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
