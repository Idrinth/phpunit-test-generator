<?php

namespace De\Idrinth\TestGenerator\Interfaces;

use SplFileInfo;

interface NamespacePathMapper
{
    /**
     * @param type $namespace
     * @return string
     */
    public function getTestNamespaceForNamespace($namespace);

    /**
     * @param string $class
     * @return SplFileInfo
     */
    public function getTestFileForNamespacedClass($class);
}
