<?php

namespace De\Idrinth\TestGenerator\Interfaces;

interface NamespacePathMapper
{
    /**
     * @param string $namespace
     * @return string
     */
    public function getTestNamespaceForNamespace($namespace);

    /**
     * @param string $class
     * @return TargetPhpFile
     */
    public function getTestFileForNamespacedClass($class);
}
