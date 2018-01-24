<?php
namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassWriter
{
    /**
     * @param ClassDescriptor $class
     * @return boolean
     */
    public function write(ClassDescriptor $class);
}
