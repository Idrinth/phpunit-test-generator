<?php
namespace De\Idrinth\TestGenerator\Service;

use De\Idrinth\TestGenerator\Model\ClassDescriptor;
use De\Idrinth\TestGenerator\Service\NamespacePathMapper;
use De\Idrinth\TestGenerator\Twig\Renderer;

class ClassWriter
{
    /**
     * @var Renderer
     */
    private $renderer;

    /**
     * @var NamespacePathMapper
     */
    private $namespaces;

    /**
     * @param NamespacePathMapper $namespaces
     * @param Renderer $renderer
     */
    public function __construct(NamespacePathMapper $namespaces, Renderer $renderer)
    {
        $this->namespaces = $namespaces;
        $this->renderer = $renderer;
    }

    /**
     * @param ClassDescriptor $class
     * @param ClassDescriptor[] $classes
     * @return boolean
     */
    public function write(ClassDescriptor $class, array $classes)
    {
        $file = $this->namespaces->getTestFileForNamespacedClass($class->getNamespace().'\\'.$class->getName());
        if (!$file->mayWrite()) {
            return false;
        }
        return $file->write($this->createContent($class, $classes));
    }

    /**
     * @param ClassDescriptor $class
     * @param ClassDescriptor[] $classes
     * @return string
     */
    private function createContent(ClassDescriptor $class, array $classes)
    {
        return $this->renderer->render(
            'class.twig',
            array(
                'class' => $class,
                'classes' => $classes,
                'config' => array(
                    'namespace' => $this->namespaces->getTestNamespaceForNamespace($class->getNamespace())
                )
            )
        );
    }
}
