<?php
namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassWriter
{
    public function write(ClassDescriptor $class);
}