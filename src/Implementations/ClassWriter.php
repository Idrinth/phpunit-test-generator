<?php
namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor as CDI;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter as CWI;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper as NPMI;
use De\Idrinth\TestGenerator\Interfaces\Renderer as RI;

class ClassWriter implements CWI
{
    /**
     * @var RI
     */
    private $renderer;

    /**
     * @var NPMI
     */
    private $namespaces;

    /**
     * @param NPMI $namespaces
     * @param RI $renderer
     */
    public function __construct(NPMI $namespaces, RI $renderer)
    {
        $this->namespaces = $namespaces;
        $this->renderer = $renderer;
    }

    /**
     * @param CDI $class
     * @param CDI[] $classes
     * @return boolean
     */
    public function write(CDI $class, $classes)
    {
        $file = $this->namespaces->getTestFileForNamespacedClass($class->getNamespace().'\\'.$class->getName());
        if (!$file->mayWrite()) {
            return false;
        }
        return $file->write($this->createContent($class, $classes));
    }

    /**
     * @param CDI $class
     * @param CDI[] $classes
     * @return string
     */
    private function createContent(CDI $class, $classes)
    {
        return $this->renderer->render(
            'class.twig',
            [
                'class' => $class,
                'classes' => $classes,
                'config' => [
                    'namespace' => $this->namespaces->getTestNamespaceForNamespace($class->getNamespace())
                ]
            ]
        );
    }
}
