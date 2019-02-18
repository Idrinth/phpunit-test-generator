<?php

namespace De\Idrinth\TestGenerator\Factory;

use De\Idrinth\TestGenerator\Factory\MethodFactory;
use De\Idrinth\TestGenerator\Model\ClassDescriptor;
use De\Idrinth\TestGenerator\Model\MethodDescriptor;
use De\Idrinth\TestGenerator\Service\TypeResolver;
use De\Idrinth\TestGenerator\Model\Type\ClassType;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;

class ClassDescriptorFactory
{
    /**
     * @var MethodFactory
     */
    private $method;

    /**
     * @param MethodFactory $method
     */
    public function __construct(MethodFactory $method)
    {
        $this->method = $method;
    }

    /**
     * @param Class_ $class
     * @param TypeResolver $resolver
     * @return ClassDescriptor
     */
    public function create(Class_ $class, TypeResolver $resolver)
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
                    $iNode->name,
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
     * @param string $methodName
     * @param MethodDescriptor $constructor by reference
     * @param MethodDescriptor[] $methods by reference
     * @param MethodDescriptor $function
     * @param string $className
     * @return void
     */
    private function addMethod(
        $methodName,
        MethodDescriptor &$constructor,
        array &$methods,
        MethodDescriptor $function,
        $className
    ) {
        if ($methodName === '__construct' || $methodName === $className) {
            $constructor = $function;
            return;
        }
        $methods[] = $function;
    }
}
