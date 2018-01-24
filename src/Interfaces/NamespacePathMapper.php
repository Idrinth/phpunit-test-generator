<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use SplFileInfo;

interface NamespacePathMapper
{
    public function getTestNamespaceForNamespace($namespace);
    /**
     * @param string $class
     * @return SplFileInfo
     */
    public function getFileForNamespacedClass($class);
}