<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface ClassDescriptor
{
    public function getName();
    public function getNamespace();
    public function getMethods();
    public function getConstructor();
}
