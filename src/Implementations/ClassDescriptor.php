<?php

namespace De\Idrinth\TestGenerator\Implementations;

class ClassDescriptor implements \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor
{
    private $name;
    private $namespace;
    private $methods = array();
    private $constructor;
    private $abstract;
    private $extends;
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

    public function getName()
    {
        return $this->name;
    }
    public function getNamespace()
    {
        return $this->namespace;
    }
    public function getMethods()
    {
        return $this->methods;
    }
    public function getConstructor()
    {
        return $this->constructor;
    }
    public function isAbstract()
    {
        return $this->abstract;
    }
    public function getExtends()
    {
        return $this->extends;
    }
}
