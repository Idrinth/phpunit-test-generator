<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use PhpParser\Node\Stmt\ClassMethod;

interface MethodFactory
{
    /**
     * @param TypeResolver $resolver
     * @param ClassMethod $method
     * @return MethodDescriptor
     */
    public function create(TypeResolver $resolver, ClassMethod $method);
}
