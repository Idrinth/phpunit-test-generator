<?php
namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor as CDI;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter as CWI;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper as NPMI;
use De\Idrinth\TestGenerator\Interfaces\Composer as CI;
use SplFileInfo;
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
     * @var CI
     */
    private $composer;

    /**
     * @param NPMI $namespaces
     * @param RI $renderer
     * @param CI $composer
     */
    public function __construct(NPMI $namespaces, RI $renderer, CI $composer)
    {
        $this->namespaces = $namespaces;
        $this->renderer = $renderer;
        $this->composer = $composer;
    }

    /**
     * @param CDI $class
     * @param CDI[] $classes
     * @param boolean $replace
     * @return boolean
     */
    public function write(CDI $class, $classes, $replace = false)
    {
        $file = $this->namespaces->getTestFileForNamespacedClass($class->getNamespace().'\\'.$class->getName());
        if (!$file instanceof SplFileInfo) {
            return false;
        }
        if (!is_dir($file->getPath()) && !mkdir($file->getPath(), 0777, true)) {
            return false;
        }
        if ($file->isFile()) {
            if (!$replace && !rename($file->getRealPath(), $file->getRealPath().date('.YmdHi').'.old')) {
                return false;
            }
        }
        return false !== file_put_contents(
            $file->getPathname(),
            $this->renderer->render(
                'class.twig',
                array(
                    'class' => $class,
                    'classes' => $classes,
                    'config' => array(
                        'namespace' => $this->namespaces->getTestNamespaceForNamespace($class->getNamespace()),
                        'testcase' => $this->composer->getTestClass()
                    )
                )
            )
        );
    }
}
