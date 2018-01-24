<?php

namespace De\Idrinth\TestGenerator\Implementations;

class ClassDescriptor implements \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor
{
    private $name;
    private $namespace;
    private $methods = array();
    private $constructor;
    public function __construct(
        $name,
        $namespace,
        array $methods,
        \De\Idrinth\TestGenerator\Interfaces\MethodDescriptor $constructor
    ) {
        $this->name = $name;
        $this->namespace = $namespace;
        $this->methods = $methods;
        $this->constructor = $constructor;
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
}