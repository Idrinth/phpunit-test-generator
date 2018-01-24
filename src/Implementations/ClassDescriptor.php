<?php

namespace De\Idrinth\TestGenerator\Implementations;

class ClassDescriptor implements \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor
{
    private $name;
    private $namespace;
    private $usages = array();
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
        $this->usages = array(
            "$namespace\\$name"
        );
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
    public function getUsages()
    {
        return $this->usages;
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