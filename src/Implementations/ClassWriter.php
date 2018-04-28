<?php
namespace De\Idrinth\TestGenerator\Implementations;

use De\Idrinth\TestGenerator\Interfaces\ClassDescriptor as CDI;
use De\Idrinth\TestGenerator\Interfaces\ClassWriter as CWI;
use De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper as NPMI;
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
     * @var string
     */
    private $mode;

    /**
     * @param NPMI $namespaces
     * @param RI $renderer
     * @param string $mode
     */
    public function __construct(NPMI $namespaces, RI $renderer, $mode)
    {
        $this->namespaces = $namespaces;
        $this->renderer = $renderer;
        $this->mode = $mode;
    }

    /**
     * @param SplFileInfo $file
     * @return boolean
     */
    private function mayWrite($file)
    {
        if (!$file instanceof SplFileInfo) {
            return false;
        }
        if ($file->isDir()) {
            return false;
        }
        if (!is_dir($file->getPath()) && !mkdir($file->getPath(), 0777, true)) {
            return false;
        }
        if (!$file->isFile()) {
            return true;
        }
        if ($this->mode === 'skip') {
            return false;
        }
        return $this->mode === 'replace' || rename($file->getPathname(), $file->getPathname().date('.YmdHi').'.old');
    }

    /**
     * @param CDI $class
     * @param CDI[] $classes
     * @return boolean
     */
    public function write(CDI $class, $classes)
    {
        $file = $this->namespaces->getTestFileForNamespacedClass($class->getNamespace().'\\'.$class->getName());
        if (!$this->mayWrite($file)) {
            return false;
        }
        return false !== file_put_contents(
            $file->getPathname(),
            $this->renderer->render(
                'class.twig',
                array(
                    'class' => $class,
                    'classes' => $classes,
                    'config' => array(
                        'namespace' => $this->namespaces->getTestNamespaceForNamespace($class->getNamespace())
                    )
                )
            )
        );
    }
}
