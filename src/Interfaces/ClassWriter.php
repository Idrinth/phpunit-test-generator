<?php
namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassWriter
{
    /**
     * @param ClassDescriptor $class
     * @param ClassDescriptor[] $classes
     * @param boolean $replace
     * @return boolean
     */
    public function write(ClassDescriptor $class, $classes, $replace = false);
}
