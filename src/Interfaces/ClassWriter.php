<?php
namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassWriter
{
    /**
     * @param ClassDescriptor $class
     * @param ClassDescriptor[] $classes
     * @return boolean
     */
    public function write(ClassDescriptor $class, $classes);
}
