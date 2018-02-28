<?php
namespace De\Idrinth\TestGenerator\Implementations;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use SplFileInfo;

class ClassWriter implements \De\Idrinth\TestGenerator\Interfaces\ClassWriter
{
    private $env;
    private $namespaces;
    public function __construct(\De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper $namespaces)
    {
        $this->namespaces = $namespaces;
        $this->env = new Environment(new FilesystemLoader(
            dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'templates'
        ));
        $this->env->addFilter(new \Twig_SimpleFilter('toUpperCamelCase', function ($string) {
            $parts = explode('_', trim(preg_replace('/[^_A-Za-z0-9]+/', '_', $string), '_'));
            $result = '';
            foreach ($parts as $part) {
                $result.= strtoupper($part{0});
                if (strlen($part)>1) {
                    $result.= substr($part, 1);
                }
            }
            return $result;
        }));
    }

    /**
     * @param \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor $class
     * @param \De\Idrinth\TestGenerator\Interfaces\ClassDescriptor[] $classes
     * @param boolean $replace
     * @return boolean
     */
    public function write(\De\Idrinth\TestGenerator\Interfaces\ClassDescriptor $class, $classes, $replace = false)
    {
        if($class->getName() === 'Controller') {
            return;
        }
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
            $this->env->render(
                'class.twig',
                array(
                    'class' => $class,
                    'classes' => $classes,
                    'config' => array(
                        'namespace' => $this->namespaces->getTestNamespaceForNamespace($class->getNamespace()),
                        'testcase' => class_exists('PHPUnit\Framework\TestCase') ?
                                'PHPUnit\Framework\TestCase' :
                                'PHPUnit_Framework_TestCase'
                    )
                )
            )
        );
    }
}
