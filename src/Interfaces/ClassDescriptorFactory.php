<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use PhpParser\Node\Stmt\Class_;

interface ClassDescriptorFactory
{
    /**
     * @param Class_ $class
     * @param TypeResolver $resolver
     */
    public function create(Class_ $class, TypeResolver $resolver);
}
