<?php

namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Implementations\Type\ClassType;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor as CDI;
use De\Idrinth\TestGenerator\Interfaces\ClassDescriptorFactory as CDFI;
use De\Idrinth\TestGenerator\Interfaces\MethodDescriptor as MDI;
use De\Idrinth\TestGenerator\Interfaces\MethodFactory as MFI;
use De\Idrinth\TestGenerator\Interfaces\TypeResolver as TRI;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class ClassDescriptorFactory implements CDFI
{
    /**
     * @var MFI
     */
    private $method;

    /**
     * @param MFI $method
     */
    public function __construct(MFI $method)
    {
        $this->method = $method;
    }

    /**
     * @param Class_ $class
     * @param TRI $resolver
     * @return CDI
     */
    public function create(Class_ $class, TRI $resolver)
    {
        $methods = array();
        $constructor = new MethodDescriptor(
            '__construct',
            array(),
            new ClassType(trim($resolver->getNamespace().'\\'.$class->name, '\\'))
        );
        foreach ($class->stmts as $iNode) {
            if ($iNode instanceof ClassMethod
                && $iNode->isPublic()
                && !$iNode->isAbstract()
            ) {
                $this->addMethod(
                    $constructor,
                    $methods,
                    $this->method->create($resolver, $iNode),
                    $class->name
                );
            }
        }
        return new ClassDescriptor(
            $class->name,
            $resolver->getNamespace(),
            $methods,
            $constructor,
            $class->isAbstract(),
            $class->extends ? $resolver->toType($class->extends, '')->getClassName() : null
        );
    }

    /**
     * @param MDI $constructor by reference
     * @param MDI[] $methods by reference
     * @param MDI $function
     * @param string $className
     * @return void
     */
    private function addMethod(MDI &$constructor, array &$methods, MDI $function, $className)
    {
        if ($function->getName() == '__construct' || $function->getName() == $className) {
            $constructor = $function;
            return;
        }
        $methods[] = $function;
    }
}