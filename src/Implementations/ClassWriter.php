<?php
namespace De\Idrinth\TestGenerator\Implementations;

use Twig_Environment;
use Twig_Loader_Filesystem;

class ClassWriter implements \De\Idrinth\TestGenerator\Interfaces\ClassWriter
{
    private $env;
    private $namespaces;
    public function __construct(\De\Idrinth\TestGenerator\Interfaces\NamespacePathMapper $namespaces)
    {
        $this->namespaces = $namespaces;
        $this->env = new Twig_Environment(new Twig_Loader_Filesystem(
            dirname(dirname(__DIR__)).DIRECTORY_SEPARATOR.'templates'
        ));
        $this->env->addFilter(new \Twig_SimpleFilter('toUpperCamelCase', function ($string) {
            $parts = explode('_', preg_replace('/[^_A-Za-z0-9]+/', '_', $string));
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
     * @return boolean
     */
    public function write(\De\Idrinth\TestGenerator\Interfaces\ClassDescriptor $class)
    {
        $file = $this->namespaces->getTestFileForNamespacedClass($class->getNamespace().'\\'.$class->getName());
        if (!$file || $file->isFile()) {
            return false;
        }
        if (!is_dir($file->getPath()) && mkdir($file->getPath(), 0777, true)) {
            return false;
        }
        return file_put_contents(
            $file->getPathname(),
            $this->env->render(
                'class.twig',
                array(
                    'class' => $class,
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
